// ID-card
// const idcard = document.getElementById("emp_idcard");

// idcard.addEventListener("input", (e) => {
//   let value = e.target.value.replace(/\D/g, "");

//   if (value.length > 12) {
//     value =
//       value.slice(0, 1) +
//       "-" +
//       value.slice(1, 5) +
//       "-" +
//       value.slice(5, 10) +
//       "-" +
//       value.slice(10, 12) +
//       "-" +
//       value.slice(12, 13);
//   } else if (value.length > 10) {
//     value =
//       value.slice(0, 1) +
//       "-" +
//       value.slice(1, 5) +
//       "-" +
//       value.slice(5, 10) +
//       "-" +
//       value.slice(10);
//   } else if (value.length > 5) {
//     value = value.slice(0, 1) + "-" + value.slice(1, 5) + "-" + value.slice(5);
//   } else if (value.length > 1) {
//     value = value.slice(0, 1) + "-" + value.slice(1);
//   }

//   e.target.value = value;
// });

document.querySelectorAll('[data-idcard]').forEach(function (el) {
    el.addEventListener('input', function () {
        var d = el.value.replace(/\D/g, '').slice(0, 13);
        var p = [d.slice(0, 1), d.slice(1, 5), d.slice(5, 10), d.slice(10, 12), d.slice(12, 13)];
        el.value = p.filter(Boolean).join('-');
    });
});

// Tel
const phoneInput = document.getElementById("phone-input");

phoneInput.addEventListener("input", (e) => {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length > 6) {
    value =
      value.slice(0, 3) + "-" + value.slice(3, 6) + "-" + value.slice(6, 10);
  } else if (value.length > 3) {
    value = value.slice(0, 3) + "-" + value.slice(3);
  } 
  e.target.value = value;
});

// ID-SSO

