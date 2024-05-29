loggedIn = "";

function showAfterLoginItems() {
  $("#login").hide();
  $("#unused-vouchers").show();
  $("#available-balance").show();
  $("#voucher-code").show();
  $("#recharge-voucher").show();
  $("#buy-gleipnir").show();
  $("#reset-data").show();
}

function showAfterLogoutItems() {
  $("#login").show();
  $("#unused-vouchers").hide();
  $("#available-balance").hide();
  $("#voucher-code").hide();
  $("#recharge-voucher").hide();
  $("#buy-gleipnir").hide();
  $("#reset-data").hide();
}

function delCookies() {
  eraseCookie("PHPSESSID");
  eraseCookie("challenge");
  eraseCookie("logged_in");
}

if ("B" == getCookie("challenge")) {
  $("#challenge").text("B");
  loggedIn = getCookie("logged_in");
  if (loggedIn) {
    showAfterLoginItems();
  } else {
    showAfterLogoutItems();
  }
} else {
  $("#challenge").text("A");
  showAfterLoginItems();
}

$("#unused-vouchers").click(function () {
  var request = "/api.php?vouchers";
  $.get(request, function (data, status) {
    if (data.success == "true") {
      vouchers = data.message;
      if (data.message == "") {
        vouchers = "No Vouchers Available";
      }
      Swal.fire("Available Vouchers", vouchers);
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: data.message,
      });
    }
  });
});

$("#available-balance").click(function () {
  var request = "/api.php?balance";
  $.get(request, function (data, status) {
    if (data.success == "true") {
      Swal.fire("Available Balance", data.message);
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: data.message,
      });
    }
  });
});

$("#recharge-voucher").click(function () {
  var card = $("#voucher-code").val();
  var request = "/api.php?card=" + card;
  $.get(request, function (data, status) {
    if (data.success == "true") {
      Swal.fire("Success", data.message, "success");
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: data.message,
      });
    }
  });
});

$("#buy-gleipnir").click(function () {
  var request = "/api.php?buyGleipnir";
  $.get(request, function (data, status) {
    if (data.success == "true") {
      Swal.fire("Success", data.message, "success");
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: data.message,
      });
    }
  });
});

$("#reset-data").click(function () {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, reset it!",
  }).then((result) => {
    if (result.value) {
      $.get("/api.php?reset", function (data, status) {
        if (data.success == "true") {
          Swal.fire("Reset!", "Date has been reset successfully.", "success");
          $("#voucher-code").val("");
          window.location.href = "#challenge1";
          delCookies();
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: data.message,
          });
        }
      });
    }
  });
});
