function alerts(v_icon, v_title, v_msg) {
  Swal.fire({
    scrollbarPadding: false,
    icon: v_icon,
    title: v_title,
    text: v_msg
  })
};
