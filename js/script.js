function verifyPasswords() {
  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirm_password").value;

  if (password !== confirmPassword) {
    alert("Les mots de passe ne correspondent pas.");
    return false;
  }
  return true;
}

$(document).ready(function () {
  $("#togglePassword").click(function () {
    console.log("Bouton");
    var passwordInput = $("#password");
    var passwordIcon = $(this).find("i");

    if (passwordInput.attr("type") === "password") {
      passwordInput.attr("type", "text");
      passwordIcon.removeClass("fa-eye").addClass("fas fa-eye-slash");
    } else {
      passwordInput.attr("type", "password");
      passwordIcon.removeClass("fas fa-eye-slash").addClass("fa-eye");
    }
  });

  $("#toggleConfirmPassword").click(function () {
    var confirmPasswordInput = $("#confirm_password");
    var confirmPasswordIcon = $(this).find("i");

    if (confirmPasswordInput.attr("type") === "password") {
      confirmPasswordInput.attr("type", "text");
      confirmPasswordIcon.removeClass("fa-eye").addClass("fas fa-eye-slash");
    } else {
      confirmPasswordInput.attr("type", "password");
      confirmPasswordIcon.removeClass("fas fa-eye-slash").addClass("fa-eye");
    }
  });
});
