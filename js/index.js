// For Theme
const lightTheme = document.querySelector(".light-theme");
const darkerTheme = document.querySelector(".darker-theme");

lightTheme.addEventListener("click", () => changeTheme("light"));
darkerTheme.addEventListener("click", () => changeTheme("darker"));

let savedTheme = localStorage.getItem("savedTheme");
savedTheme === null
  ? changeTheme("darker")
  : changeTheme(localStorage.getItem("savedTheme"));

// Change theme function:
function changeTheme(color) {
  localStorage.setItem("savedTheme", color);
  savedTheme = localStorage.getItem("savedTheme");

  document.body.className = color;
  // Change blinking cursor for darker theme:
  color === "darker"
    ? document.getElementById("title").classList.add("darker-title")
    : document.getElementById("title").classList.remove("darker-title");

  document
    .getElementById("form")
    .querySelector("input")
    .classList.add(`${color}-input`);
  document
    .getElementById("form")
    .querySelector("textarea")
    .classList.add(`${color}-input`);
  // document.querySelector('input').classList.add(`${color}-input`);
  // document.querySelector('textarea').classList.add(`${color}-input`);
  // Change todo color without changing their status (completed or not):
  document.querySelectorAll(".todo").forEach((todo) => {
    Array.from(todo.classList).some((item) => item === "completed")
      ? (todo.className = `todo ${color}-todo completed`)
      : (todo.className = `todo ${color}-todo`);
  });
  // Change buttons color according to their type (todo, check or delete):
  document.querySelectorAll("button").forEach((button) => {
    Array.from(button.classList).some((item) => {
      if (item === "check-btn") {
        button.className = `check-btn ${color}-button`;
      } else if (item === "details-btn") {
        button.className = `details-btn ${color}-button`;
      } else if (item === "update-btn") {
        button.className = `update-btn ${color}-button`;
      } else if (item === "delete-btn") {
        button.className = `delete-btn ${color}-button`;
      } else if (item === "todo-btn") {
        button.className = `todo-btn ${color}-button`;
      }
    });
  });
}

// For Input Todo-Title
const todoTitleInput = document.getElementById("todo_title");
const errorElement = document.getElementById("todo_title_error");
const addButton =
  document.querySelector(".todo-btn") || document.querySelector(".confirm-btn");

todoTitleInput.addEventListener("input", function () {
  const inputValue = todoTitleInput.value;

  if (inputValue.length > 50) {
    errorElement.textContent = "Exceeded 50 Characters!!";
    addButton.disabled = true;
  } else {
    errorElement.textContent = ""; // Clear the error message if within limit
    addButton.disabled = false;
  }
});

// For Validation

function clearErrors() {
  let errors = document.getElementsByClassName("text-error");
  for (let i = 0; i < errors.length; i++) {
    errors[i].innerHTML = "";
  }
}

function validateRegistrationForm() {
  clearErrors();
  let error = false;
  let useremail = document.forms["registrationForm"]["useremail"].value.trim();
  let username = document.forms["registrationForm"]["username"].value.trim();
  let userpassword =
    document.forms["registrationForm"]["userpassword"].value.trim();
  if (useremail == null || useremail == "") {
    document.getElementsByClassName("text-error")[0].innerHTML =
      "* " + "The Email Must Be Given";
    error = true;
  }

  if (username == null || username == "") {
    document.getElementsByClassName("text-error")[1].innerHTML =
      "* " + "The Name Must Be Given";
    error = true;
  }

  if (userpassword == null || userpassword == "") {
    document.getElementsByClassName("text-error")[2].innerHTML =
      "* " + "The Password Must Be Given";
    error = true;
  }

  if (error) {
    return false;
  }
}

function validateLoginForm() {
  clearErrors();
  let error = false;
  let useremail = document.forms["loginForm"]["useremail"].value.trim();
  let userpassword = document.forms["loginForm"]["userpassword"].value.trim();

  if (useremail == null || useremail == "") {
    document.getElementsByClassName("text-error")[0].innerHTML =
      "* " + "The Email Must Be Given";
    error = true;
  }

  if (userpassword == null || userpassword == "") {
    document.getElementsByClassName("text-error")[1].innerHTML =
      "* " + "The Password Must Be Given";
    error = true;
  }

  if (error) {
    return false;
  }
}
