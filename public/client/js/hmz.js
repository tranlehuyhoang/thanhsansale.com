//  Login
function login() {
  // class name
  var username = $("#UsernameLogin").val();
  var password = $("#PasswordLogin").val();
  const data = {
    Username: username,
    Password: password,
  };
  $.ajax({
    url: "/auth/login",
    method: "POST",
    data: data,
    success: function (res) {
      if (res.statusCode == 200) {
        Swal.fire("Đăng nhập thành công!", res.message, "success");
        // set local storage
        setTimeout(function () {
          window.location.href = "/";
        }, 1500);
      } else if (res.statusCode == 401) {
        Swal.fire(
          "Tài khoản chưa được xác thực!",
          "Bạn sẽ được chuyển hướng",
          "info"
        );
        setTimeout(function () {
          // Ensure username is defined
          if (typeof username !== "undefined") {
            // check if username is an email
            username = username.includes("@")
              ? username.split("@")[0]
              : username;
            window.location.href = "/auth/verify/" + username;
          }
        }, 1000);
      } else {
        Swal.fire("Đăng nhập thất bại!", res.message, "error");
      }
    },
    error: function (err) {
      console.log(err);
      Swal.fire({
        icon: "error",
        title: err.message,
        showConfirmButton: false,
        timer: 1500,
      });
    },
  });
}
//  Register
function register() {
  // class name
  var username = $("#Username").val();
  var password = $("#Password").val();
  var confirmPassword = $("#ConfirmPassword").val();
  var email = $("#Email").val();
  // google recaptcha
  var token = grecaptcha.getResponse();
  const data = {
    Username: username,
    Password: password,
    ConfirmPassword: confirmPassword,
    Email: email,
    Token: token,
  };

  grecaptcha.ready(function () {
    $.ajax({
      url: "/auth/register",
      method: "POST",
      data: data,
      beforeSend: function () {
        Swal.fire({
          icon: "info",
          title: "Đang xử lý...",
          onBeforeOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function (res) {
        if (res.success == true) {
          Swal.fire("Đăng ký thành công!", res.message, "success");
          setTimeout(function () {
            window.location.href = "/auth/verify/" + username;
          }, 1500);
        } else if (res.statusCode == 401) {
          Swal.fire("Đăng ký thất bại!", res.message, "error");
          setTimeout(function () {
            window.location.href = "/auth/verify/" + username;
          }, 1000);
        } else {
          // reset captcha
          grecaptcha.reset();
          Swal.fire("Đăng ký thất bại!", res.message, "error");
        }
      },
      error: function (err) {
        console.log(err);
        Swal.fire({
          icon: "error",
          title: err.message,
          showConfirmButton: false,
          timer: 1500,
        });
      },
    });
  });
}
