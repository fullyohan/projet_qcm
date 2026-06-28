<?php
    session_start();
    require_once './security/auth-guard.php';
    require_auth();
    
    $module = isset($_POST['module']) ? htmlspecialchars($_POST['module']) : '';
    if (empty($module)) {
        header("Location: /user/dashboard");
        exit();
    }
    require_once '../config/db.php'; 
    $sql = "SELECT id, question, answer1, answer2, answer3, answer4 
            FROM questions 
            WHERE module = ?
            ORDER BY RAND() 
            LIMIT 10";      
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $module);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $questions = [];

    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'id' => (int)$row['id'],
            'question' => $row['question'],
            'choices' => [
                1 => $row['answer1'],
                2 => $row['answer2'],
                3 => $row['answer3'],
                4 => $row['answer4']
            ]
        ];
    }

    if (empty($questions)) {
        header('Location: /user/dashboard');
        exit();
    }
    
    $questions_json = json_encode($questions, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen en cours - QCMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/quiz.css">
    
</head>
<style>

#fullscreen-overlay {
    position: fixed;
    inset: 0;
    background: var(--emerald-main);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: white;
    text-align: center;
    padding: 2rem;
}
#fullscreen-overlay .overlay-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}
#fullscreen-overlay h2 {
    font-weight: 800;
    font-size: 1.8rem;
    margin-bottom: 1rem;
}
#fullscreen-overlay p {
    opacity: 0.85;
    max-width: 480px;
    margin-bottom: 2rem;
    line-height: 1.6;
}
#btn-request-fullscreen {
    background: white;
    color: var(--emerald-main);
    font-weight: 700;
    border: none;
    padding: 0.85rem 2.5rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s;
}
#btn-request-fullscreen:hover {
    background: var(--emerald-soft);
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
#warning-banner {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 5000;
    background: #dc2626;
    color: white;
    text-align: center;
    padding: 0.75rem 1rem;
    font-weight: 600;
    font-size: 0.95rem;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown {
    from { transform: translateY(-100%); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

#warning-counter-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: none;
    color: #dc2626;
    border-radius: 2rem;
    padding: 0.25rem 0.85rem;
    font-size: 0.8rem;
    font-weight: 700;
}


</style>
<body>
    <div id="fullscreen-overlay">
        <div class="overlay-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <h2>Mode plein écran requis</h2>
        <p>
            Pour garantir l'intégrité de cet examen, vous devez activer le mode plein écran.<br>
            Quitter le plein écran ou changer d'onglet déclenchera un avertissement.<br>
            <strong>Après 3 avertissements, l'examen sera soumis automatiquement.</strong>
        </p>
        <button id="btn-request-fullscreen" onclick="requestFullscreenAndStart()">
            <i class="fa-solid fa-expand me-2"></i>Activer le plein écran pour continuer
        </button>
    </div>

    <div id="warning-banner">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <span id="warning-message">Avertissement</span>
    </div>

    <nav class="navbar navbar-custom shadow-sm">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                    <i class="fa-solid fa-graduation-cap text-success me-1"></i><?= $module ?>
                </span>
            </div>
            <div id="warning-counter-badge">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span id="warning-count-label">0 / 3 avertissements</span>
            </div>
        </div>
    </nav>

    <main class="container quiz-container" id="quiz-ui">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Progression du test</span> 
                        <span id="timer-display">10:00</span>
                        <span class="text-dark small fw-bold" id="progress-text">0 / 0 Questions</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%;"></div>
                    </div>
                </div>

                <div id="question-box"></div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-muted" id="btn-prev" onclick="changeQuestion(-1)">
                        <i class="fa-solid fa-arrow-left me-2 small"></i>Précédent
                    </button>
                    <button type="button" class="btn btn-emerald px-4 py-2 shadow-sm" id="btn-next" onclick="changeQuestion(1)">
                        Suivant <i class="fa-solid fa-arrow-right ms-2 small"></i>
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card content-card shadow-none p-4">
                    <p class="text-muted small mb-4">Cliquez sur un numéro pour naviguer directement vers la question correspondante.</p>
                    <div class="d-flex flex-wrap gap-2 mb-4" id="roadmap-badges"></div>
                    <button class="btn btn-outline-danger btn-sm w-100 mt-4 py-2 fw-semibold" onclick="submitExam()">
                        Soumettre et terminer
                    </button>
                </div>
            </div>

        </div>
    </main>

<script>
    const showAlert = (title, message, type = 'info', onConfirm = null) => {
        const isConfirm = type === 'confirm';
        const icon = type === 'error'
            ? 'fa-circle-xmark text-danger'
            : (isConfirm ? 'fa-circle-question text-warning' : 'fa-circle-info text-primary');
        const btnClass = type === 'error' ? 'btn-danger' : 'btn-emerald';
        const btnText  = type === 'error' ? 'Revenir en arrière' : (isConfirm ? 'Confirmer' : 'OK');

        const modalWrapper = document.createElement('div');
        modalWrapper.innerHTML = `
            <div class="modal fade show d-block" style="background:rgba(0,0,0,0.5);z-index:1060;" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow" style="border-radius:1.25rem;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dark">${title}</h5>
                            <button type="button" class="btn-close btn-close-modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <div class="d-flex align-items-start gap-3">
                                <span class="fs-3"><i class="fa-solid ${icon}"></i></span>
                                <p class="text-secondary mb-0 align-self-center">${message}</p>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light border fw-medium px-4 btn-close-modal ${!isConfirm ? 'd-none' : ''}" style="border-radius:0.5rem;">Annuler</button>
                            <button type="button" class="btn ${btnClass} px-4 btn-confirm-modal" style="border-radius:0.5rem;">${btnText}</button>
                        </div>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(modalWrapper);
        const closeModal = () => modalWrapper.remove();
        modalWrapper.querySelectorAll('.btn-close-modal').forEach(btn => btn.onclick = closeModal);
        modalWrapper.querySelector('.btn-confirm-modal').onclick = () => { closeModal(); if (onConfirm) onConfirm(); };
    };

   
    const CURRENT_MODULE = "<?= $module; ?>";
    let questionsList   = <?= $questions_json; ?>;
    let userResponses   = {};
    let currentIndex    = 0;
    let examTimer       = null;
    let timeLeft        = 600;
    let warningCount    = 0;
    let examStarted     = false;
    let isSubmitting    = false;

    const escapeHtml = text => text
        ? text.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")
              .replace(/"/g,"&quot;").replace(/'/g,"&#039;")
        : "";

    const formatTime = seconds =>
        `${Math.floor(seconds/60).toString().padStart(2,'0')}:${(seconds%60).toString().padStart(2,'0')}`;

    const requestFullscreenAndStart = () => {
        document.documentElement.requestFullscreen()
            .then(() => {
                document.getElementById('fullscreen-overlay').style.display = 'none';
                startExam();
            })
            .catch(() => {
                showAlert(
                    "Plein écran refusé",
                    "Vous devez autoriser le mode plein écran pour démarrer l'examen.",
                    'error',
                );
            });
    };

    document.addEventListener('contextmenu', (e) => e.preventDefault());
    document.addEventListener('copy', (e) => e.preventDefault());
    document.addEventListener('paste', (e) => e.preventDefault());
    document.addEventListener('selectstart', (e) => e.preventDefault());

    const showWarningBanner = msg => {
        const banner = document.getElementById('warning-banner');
        document.getElementById('warning-message').textContent = msg;
        banner.style.display = 'block';
        setTimeout(() => { banner.style.display = 'none'; }, 4000);
    };

    
    const triggerWarning = reason => {
        if (!examStarted || isSubmitting) return;
        warningCount++;
        document.getElementById('warning-count-label').textContent = `${warningCount} / 3 avertissements`;

        if (warningCount >= 3) {
            showWarningBanner(`${reason} — 3 avertissements atteints. L'examen va être soumis.`);
            setTimeout(() => forceSubmitExam(), 2000);
        } else {
            showWarningBanner(`${reason} — Avertissement ${warningCount}/3`);
        }
    };

    document.addEventListener('fullscreenchange',       onFullscreenChange);
    document.addEventListener('webkitfullscreenchange', onFullscreenChange);
    document.addEventListener('mozfullscreenchange',    onFullscreenChange);
    document.addEventListener('MSFullscreenChange',     onFullscreenChange);

    function onFullscreenChange() {
        const isFs = !!(document.fullscreenElement || document.webkitFullscreenElement
                     || document.mozFullScreenElement || document.msFullscreenElement);
        if (!isFs && examStarted && !isSubmitting) {
            document.getElementById('fullscreen-overlay').style.display = 'flex';
            triggerWarning("Vous avez quitté le plein ecran");
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden' && examStarted && !isSubmitting) {
            triggerWarning("Vous avez changé d'onglet ou quitté la fenêtre");
        }
    });

    const startExam = () => {
        examStarted = true;
        initRoadmap();
        renderQuestion();
        startCountdown();
    };

    const updateSidebarStats = () => {
        document.querySelectorAll('.q-badge').forEach((badge, index) => {
            badge.classList.remove('active');
            if (index === currentIndex) badge.classList.add('active');
        });
    };

    const jumpToQuestion = index => {
        currentIndex = index;
        renderQuestion();
    };

    const changeQuestion = direction => {
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = 0;
        if (currentIndex >= questionsList.length) currentIndex = questionsList.length - 1;
        renderQuestion();
    };

    const selectOption = (cardElement, questionId, choiceId) => {
        cardElement.querySelector('input[type="radio"]').checked = true;
        userResponses[String(questionId)] = choiceId;
        document.getElementById(`badge-${currentIndex}`).classList.add('answered');
        renderQuestion();
    };

    const initRoadmap = () => {
        const container = document.getElementById('roadmap-badges');
        container.innerHTML = "";
        questionsList.forEach((_, index) => {
            const badge = document.createElement('span');
            badge.className = `q-badge ${index === 0 ? 'active' : ''}`;
            badge.id = `badge-${index}`;
            badge.innerText = index + 1;
            badge.onclick = () => jumpToQuestion(index);
            container.appendChild(badge);
        });
    };

    const renderQuestion = () => {
        if (questionsList.length === 0) return;

        const currentQ    = questionsList[currentIndex];
        const savedResp   = userResponses[String(currentQ.id)] ?? null;

        document.getElementById('progress-text').innerText =
            `${currentIndex + 1} / ${questionsList.length} Questions`;
        document.getElementById('progress-bar').style.width =
            `${((currentIndex + 1) / questionsList.length) * 100}%`;
        document.getElementById('btn-prev').disabled = (currentIndex === 0);

        const btnNext = document.getElementById('btn-next');
        if (currentIndex === questionsList.length - 1) {
            btnNext.innerHTML = `Terminer l'examen <i class="fa-solid fa-check ms-2 small"></i>`;
            btnNext.onclick = submitExam;
        } else {
            btnNext.innerHTML = `Suivant <i class="fa-solid fa-arrow-right ms-2 small"></i>`;
            btnNext.onclick = () => changeQuestion(1);
        }

        const choicesHtml = Object.entries(currentQ.choices).map(([choiceId, choiceText]) => `
            <div class="option-card w-100 d-flex align-items-center mb-2 px-3 py-2 ${savedResp == choiceId ? 'selected' : ''}"
                 onclick="selectOption(this, ${currentQ.id}, ${choiceId})">
                <div class="form-check w-100 m-0 d-flex align-items-center">
                    <input class="form-check-input my-0 me-3" type="radio"
                           name="q_${currentQ.id}" id="choice_${choiceId}"
                           value="${choiceId}" ${savedResp == choiceId ? 'checked' : ''}>
                    <label class="form-check-label text-dark w-100 py-2"
                           for="choice_${choiceId}" style="cursor:pointer;">
                        ${escapeHtml(choiceText)}
                    </label>
                </div>
            </div>`
        ).join('');

        document.getElementById('question-box').innerHTML = `
            <div class="card content-card shadow-none mb-4">
                <h4 class="fw-bold text-dark mb-4">${escapeHtml(currentQ.question)}</h4>
                <div class="options-list">${choicesHtml}</div>
            </div>`;

        updateSidebarStats();
    };

   
    const executeSubmission = async () => {
        if (isSubmitting) return;
        isSubmitting = true;
        clearInterval(examTimer);

        try {
            const response = await fetch('handlers/review-answers.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    module: CURRENT_MODULE,
                    question_ids: questionsList.map(q => q.id),
                    responses: userResponses
                })
            });

            if (!response.ok) throw new Error("Erreur serveur lors de la validation.");
            const data = await response.json();

            if (data.status === 'success') {
                window.location.href = `/result.php?id=${data.attempt_id}`;
            } else {
                showAlert("Oops !", `Erreur : ${data.message}`, 'error',
                    () => window.location.href = '/user/dashboard');
            }
        } catch (err) {
            showAlert("Oops !", `Erreur réseau : ${err.message}`, 'error',
                () => window.location.href = '/user/dashboard');
        }
    };

    const forceSubmitExam = () => {
        questionsList.forEach(q => {
            if (userResponses[String(q.id)] === undefined) userResponses[String(q.id)] = null;
        });
        executeSubmission();
    };

    const submitExam = () => {
        const answeredCount = Object.keys(userResponses).length;
        if (answeredCount < questionsList.length) {
            showAlert(
                "Terminer l'examen ?",
                `Vous avez répondu à ${answeredCount} sur ${questionsList.length} questions. Les questions sans réponse seront comptées comme incorrectes. Voulez-vous quand même soumettre ?`,
                'confirm',
                () => forceSubmitExam()
            );
            return;
        }
        executeSubmission();
    };

    const startCountdown = () => {
        const timerDisplay = document.getElementById('timer-display');
        examTimer = setInterval(() => {
            timeLeft--;
            timerDisplay.innerText = formatTime(timeLeft);
            if (timeLeft <= 60) timerDisplay.className = 'text-danger fw-bold';
            if (timeLeft <= 0) {
                clearInterval(examTimer);
                forceSubmitExam();
            }
        }, 1000);
    };
</script>
</body>
</html>
