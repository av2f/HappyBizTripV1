// allow to show toast message
import Swal from 'sweetalert2/src/sweetalert2.js'
const Toast = Swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 4000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
Toast.fire({
  icon: document.getElementById('toast').dataset.icon,
  title: document.getElementById('toast').dataset.message
})
