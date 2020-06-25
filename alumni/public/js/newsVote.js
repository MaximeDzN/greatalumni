var voteValue = document.getElementById("voteValue");
var scoreValue = document.getElementById("score_value");
var voteDIV = document.getElementById("voteDIV");
var form = document.getElementById("score_form");
var stars = [document.getElementById("starA"), document.getElementById("starB"), document.getElementById("starC"), document.getElementById("starD"), document.getElementById("starE")]
var voteState = document.getElementById("voteState");

function vote() {

    if (voteValue != "") {

        if (voteValue.value == 0) {
            stars.forEach(star => {
                star.classList.remove("checked");
            });
        } else if (voteValue.value >= 1 && voteValue.value < 2) {
            stars[0].classList.add("checked");
            for (let i = 1; i < stars.length; i++) {
                stars[i].classList.remove("checked");
            }
        } else if (voteValue.value >= 2 && voteValue.value < 3) {
            for (let i = 1; i >= 0; i--) {
                stars[i].classList.add("checked");
            }
            for (let i = 2; i < stars.length; i++) {
                stars[i].classList.remove("checked");
            }
        } else if (voteValue.value >= 3 && voteValue.value < 4) {
            for (let i = 2; i >= 0; i--) {
                stars[i].classList.add("checked");
            }
            for (let i = 3; i < stars.length; i++) {
                stars[i].classList.remove("checked");
            }
        } else if (voteValue.value >= 4 && voteValue.value < 5) {
            for (let i = 3; i >= 0; i--) {
                stars[i].classList.add("checked");
            }
            for (let i = 4; i < stars.length; i++) {
                stars[i].classList.remove("checked");
            }
        } else if (voteValue.value == 5) {
            stars.forEach(star => {
                star.classList.add("checked");
            });
        }
    }

}

vote();
stars[0].onmouseover = function() {
    stars[0].classList.add("checked");
    for (let i = 1; i < stars.length; i++) {
        stars[i].classList.remove("checked");
    }
    scoreValue.value = 1;
}

stars[1].onmouseover = function() {
    for (let i = 1; i >= 0; i--) {
        stars[i].classList.add("checked");
    }
    for (let i = 2; i < stars.length; i++) {
        stars[i].classList.remove("checked");
    }
    scoreValue.value = 2;
}


stars[2].onmouseover = function() {
    for (let i = 2; i >= 0; i--) {
        stars[i].classList.add("checked");
    }
    for (let i = 3; i < stars.length; i++) {
        stars[i].classList.remove("checked");
    }
    scoreValue.value = 3;
}


stars[3].onmouseover = function() {
    for (let i = 3; i >= 0; i--) {
        stars[i].classList.add("checked");
    }
    for (let i = 4; i < stars.length; i++) {
        stars[i].classList.remove("checked");
    }
    scoreValue.value = 4;
}


stars[4].onmouseover = function() {
    stars.forEach(star => {
        star.classList.add("checked");
    });
    scoreValue.value = 5;
}

voteDIV.onmouseleave = function() {
    vote();
}

stars.forEach(star => {
    star.onclick = function() {
        if (voteState.value == false) {
            form.submit();
        } else {
            alert("vous avez déjà voté");
        }
    }
});