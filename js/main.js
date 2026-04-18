(function () {
    "use strict";

    function showInlineError(input, message) {
        input.setCustomValidity(message);
        input.reportValidity();
    }

    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            const username = document.getElementById("username");
            const email = document.getElementById("email");
            const password = document.getElementById("password");

            if (username && username.value.trim().length < 3) {
                event.preventDefault();
                showInlineError(username, "Username must be at least 3 characters.");
                return;
            }

            if (password && password.value.length < 6) {
                event.preventDefault();
                showInlineError(password, "Password must be at least 6 characters.");
                return;
            }

            if (email && !/^\S+@\S+\.\S+$/.test(email.value)) {
                event.preventDefault();
                showInlineError(email, "Enter a valid email.");
            }
        });
    }

    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (event) {
            const message = document.getElementById("message");
            if (message && message.value.trim().length < 10) {
                event.preventDefault();
                showInlineError(message, "Message should be at least 10 characters.");
            }
        });
    }

    const timerDisplay = document.getElementById("timerDisplay");
    const startTimerBtn = document.getElementById("startTimer");
    const resetTimerBtn = document.getElementById("resetTimer");
    const timerCard = document.getElementById("timerCard");

    if (timerDisplay && startTimerBtn && resetTimerBtn) {
        let totalSeconds = 25 * 60;
        let intervalId = null;

        const updateTimer = function () {
            const min = String(Math.floor(totalSeconds / 60)).padStart(2, "0");
            const sec = String(totalSeconds % 60).padStart(2, "0");
            timerDisplay.textContent = min + ":" + sec;
        };

        updateTimer();

        startTimerBtn.addEventListener("click", function () {
            if (intervalId !== null) {
                return;
            }

            if (timerCard) {
                timerCard.classList.add("timer-running");
            }

            intervalId = setInterval(function () {
                totalSeconds -= 1;
                updateTimer();

                if (totalSeconds <= 0) {
                    clearInterval(intervalId);
                    intervalId = null;
                    if (timerCard) {
                        timerCard.classList.remove("timer-running");
                    }
                    alert("Pomodoro finished. Take a short break.");
                }
            }, 1000);
        });

        resetTimerBtn.addEventListener("click", function () {
            clearInterval(intervalId);
            intervalId = null;
            totalSeconds = 25 * 60;
            if (timerCard) {
                timerCard.classList.remove("timer-running");
            }
            updateTimer();
        });
    }
})();
