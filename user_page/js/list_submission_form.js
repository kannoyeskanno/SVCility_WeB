const form1 = document.getElementById("form1");
const nextButton1 = document.getElementById("nextButton1");
const form2 = document.getElementById("form2");
const prevButton2 = document.getElementById("prevButton2");
const nextButton2 = document.getElementById("nextButton2");
const dot1 = document.querySelector(".dot1");
const dot2 = document.querySelector(".dot2");

nextButton1.addEventListener("click", (e) => {
    e.preventDefault();
    document.querySelector(".page1").classList.remove("active");
    document.querySelector(".page2").classList.add("active");
    dot2.classList.add("active");
});

prevButton2.addEventListener("click", (e) => {
    e.preventDefault();
    document.querySelector(".page1").classList.add("active");
    document.querySelector(".page2").classList.remove("active");
    dot1.classList.add("active");
    dot2.classList.remove("active");
});

nextButton2.addEventListener("click", (e) => {
    e.preventDefault();
    if (document.getElementById("subject").value === "") {
        // Subject is blank, show a message or take necessary action
        alert("Subject is required!");
    } else {
        // Proceed with form submission
        alert("Form submitted!");
    }
});

const choicesContainer = document.getElementById("purpose");

document.querySelectorAll("#choices-container button").forEach((button) => {
    button.addEventListener("click", () => {
        const newChoice = document.createElement("button");
        newChoice.textContent = button.textContent; // Use the text of the clicked button
        newChoice.classList.add("choice_list");
        newChoice.addEventListener("click", () => {
            document.getElementById("purpose").value += " " + newChoice.textContent;
        });
        choicesContainer.appendChild(newChoice);
    });
});

const fileInput = document.getElementById("file");
const fileDisplay = document.querySelector(".file-display");

fileInput.addEventListener("change", function () {
    const selectedFile = fileInput.files[0];
    if (selectedFile) {
        const fileName = selectedFile.name;
        fileDisplay.innerHTML = `
            <i class="bi bi-file-earmark"></i> ${fileName}`;
    } else {
        fileDisplay.innerHTML = `<i class="bi bi-file-earmark"></i> No file selected`;
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const page1 = document.querySelector(".page1");
    const page2 = document.querySelector(".page2");
    const nextButton1 = document.getElementById("nextButton1");
    const nextButton2 = document.getElementById("nextButton2");
    const prevButton2 = document.getElementById("prevButton2");

    nextButton1.addEventListener("click", function () {
        page1.classList.remove("active");
        page2.classList.add("active");
    });

    nextButton2.addEventListener("click", function () {
    });

    prevButton2.addEventListener("click", function () {
        page1.classList.add("active");
        page2.classList.remove("active");
    });
});