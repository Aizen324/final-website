const signUpBtn = document.querySelector('.js-signUpBtn');
const loginBtn = document.querySelector('.js-loginBtn');
const container = document.querySelector('.box-container');
const inputs = document.querySelectorAll('.js-input');
const signUpInputs = document.querySelectorAll('.sign-up .js-input');
const loginInputs = document.querySelectorAll('.login .js-input');
const signUpForm = document.querySelector('.sign-up form');
const loginForm = document.querySelector('.login form');

// =================================
/* This blocks of code gives functionality to the buttons 
that allow the user to switch between login and signup forms. */

signUpBtn.addEventListener('click', ()=>{
  container.classList.add("active");
  clearInputBox();
  });

  loginBtn.addEventListener('click', ()=>{
    container.classList.remove("active");
    clearInputBox();
  });
// //==================================

// Error Handling

signUpForm.addEventListener('submit', (e)=>{  
  let hasError = false;

  signUpInputs.forEach(input => {
    if (input.value.trim() === "") {
      toggleError(input, true);
      hasError = true;
    } else {
      toggleError(input, false);
    }
  });

  if (hasError) e.preventDefault();
});

loginForm.addEventListener('submit', (e)=>{
  let hasError = false;
  
  loginInputs.forEach(input => {
    if (input.value.trim() === "") {
      toggleError(input, true)
      hasError = true;
    } else {
      toggleError(input, false)
    }
  })

  if (hasError) e.preventDefault();
});

// Functions
function toggleError(input, state) {
  // changes border based on validation result
  const errorMessage = input.parentElement.querySelector('.err-message');

  if (state) {
    input.classList.remove('input-error');
    void input.offsetWidth;
    input.classList.add('input-error');
    input.style.border = "2px solid #ff0000";

    if (errorMessage) errorMessage.style.display = "block";
  } else {
    input.classList.remove('input-error');
    input.style.border = "none";
    if (errorMessage) errorMessage.style.display = "none";
  }
}

function clearInputBox() {
  inputs.forEach(input => {
    const errorMessage = input.parentElement.querySelector('.err-message');
    input.value = "";
    input.style.border = "none";

    if (errorMessage) errorMessage.style.display = "none";
  });
}
















// const loginSubmitBtn = document.querySelector('.js-login-submit');
// const signUpSubmitBtn = document.querySelector('.js-signup-submit');


// loginSubmitBtn.addEventListener("click", (e) => {
//   e.preventDefault();

//   // Compare data with input.
//   const email = inputs[0].value;
//   const password = toBinary(inputs[1].value);
//   const user = data.find(user => user.email === email && user.password === password);
  
//   if (user) {
//     alert("Logged In Successfully!");
//     localStorage.setItem("currentUser", JSON.stringify(user));
//     clearInputBox();
//     window.location.replace("home.php");
//   } else { 
//     loginErrMessage.textContent = "Invalid email or password";
//     addRedBorder([inputs[0], inputs[1]], true);
//   }
// });


// signUpSubmitBtn.addEventListener("click", (e) => {
  
//   e.preventDefault();
  
//   // =================================================
//   // Checks if information is already present in the local array.
//   // The local array and storage serves as the database since we didn't use any DBMS.
//   const name = inputs[2].value;
//   const email = inputs[3].value;
//   const password = toBinary(inputs[4].value);
  

//   if (name && emailValidation(email) && password) {
//     if (data.some(user => user.email === email)) {
//       alert("Account already exists.");
//       return;
//     }

//     data.push({"name": name, "email": email, "password": password, "rehomingData": []});
  
//     localStorage.setItem("data", JSON.stringify(data));
//     alert("Successfully Signed Up! Proceed to Login.");

//     inputs[2].value = "";
//     inputs[3].value = "";
//     inputs[4].value = "";

//     clearInputBox();
//     location.reload();
//   } else {
//     if (!name) {
//       nameErrMessage.textContent = "Invalid name."; 
//       addRedBorder([inputs[2]], true);
//     } else {
//       nameErrMessage.textContent = "";
//       addRedBorder([inputs[2]], false);
//     }
          
//     if (!emailValidation(email)) {
//       emailErrMessage.textContent = "Invalid email address.";
//       addRedBorder([inputs[3]], true);
//     } else  {
//       emailErrMessage.textContent = "";
//       addRedBorder([inputs[3]], false);
//     }
    
//     if (!password) {
//       passwordErrMessage.textContent = "Invalid password.";
//       addRedBorder([inputs[4]], true);
//     } else {
//       passwordErrMessage.textContent = "";
//       addRedBorder([inputs[4]], false);
//     }
//   }

// });



// function emailValidation(email) {
//   // validates email input in sign up
//   // used regular expression (regex)
//   const regex = /^[a-zA-Z0-9._-]+@[a-zA-z0-9.-]+\.[a-zA-Z]{2,}$/;
//   return regex.test(email);
// }

// function toBinary(password) {
//   // specifically for passwords for better encryption since we didn't use dbms
//   let convertedResult = "";
//   for (let i = 0; i < password.length; i++) {
//     convertedResult += password[i].charCodeAt().toString(2) + " ";
//   }

//   return convertedResult;
// }