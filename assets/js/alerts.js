function alerts(v_icon, v_title, v_msg) {
  Swal.fire({
    scrollbarPadding: false,
    icon: v_icon,
    title: v_title,
    text: v_msg
  })
};

function now_developer() {
  Swal.fire({
    icon: 'info',
    title: 'Упс...',
    text: 'Данная функция находится в разработке. Пожалуйста дождитесь ее полной реализации на платформе. Благодарим за ожидание :)'
  })
};
