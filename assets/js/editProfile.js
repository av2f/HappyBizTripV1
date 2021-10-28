// ***** Handle interest choices *****
// initialize value of edit_profile_listInterest
window.addEventListener('load', () => {
  var arrayListInterest = []
  document.querySelectorAll('.control-interest').forEach(interest => {
    if (interest.checked) {
      // Retrieve id of the interest and store in array
      var strInterest = interest.getAttribute('id')
      var interestId = strInterest.substring(strInterest.indexOf('_', 1) + 1, strInterest.length)
      arrayListInterest.push(interestId)
    }
  })
  // set to input hidden 'edit_profile_ListInterest' values of arrayListInterest
  document.getElementById('edit_profile_listInterest').value = arrayListInterest.join(';')
})

// retrieve all interests and listen on click
document.querySelectorAll('.control-interest').forEach(interest =>
  interest.addEventListener('click', () => {
    var listInterest = document.getElementById('edit_profile_listInterest').value
    // retrieve the id of interest
    var strInterest = interest.getAttribute('id')
    var interestId = strInterest.substring(strInterest.indexOf('_', 1) + 1, strInterest.length)
    // action following checked or not
    if (interest.checked) {
      listInterest = updateListInterest(listInterest, interestId, 'A')
    } else {
      listInterest = updateListInterest(listInterest, interestId, 'R')
    }
    document.getElementById('edit_profile_listInterest').value = listInterest
  })
)
// ***** end of Handle interest choices *****

// ***** Handle of avatar *****
// Handle of cancel button in modal window
var btnCancel = false

// Load image when modal window is opened
$('#avatarProfileModal').on('show.bs.modal', () => {
  document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('imgAvatarProfile').getAttribute('src'))
})

// When click on cancel button on modal window
document.getElementById('btnCancelAvatar').addEventListener('click', () => {
  console.log('Click bouton annuler')
  if (!btnCancel) { btnCancel = true }
})

// When click on close button on modal window
document.getElementById('btnCloseButton').addEventListener('click', () => {
  console.log('click sur bouton close')
  if (!btnCancel) { btnCancel = true }
})

/*  Click delete button on avatar modal windows
    1. Replace picture by default avatar
    2. add class 'modal-default-avatar' to reduce size of default avatar
    3. Disabled button delete
    4. reset the value of input file
*/
document.getElementById('btnDelAvatar').addEventListener('click', () => {
  document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
  document.getElementById('imgModalAvatar').classList.add('modal-default-avatar')
  document.getElementById('btnDelAvatar').setAttribute('disabled', 'disabled')
  document.getElementById('uploadFile').value = ''
})

// Click on button Modify
document.getElementById('btnChangeAvatar').addEventListener('click', () => {
  document.getElementById('uploadFile').click()
})

// Handle change of picture
const fileInput = document.getElementById('uploadFile')
fileInput.addEventListener('change', () => {
  var fileAvatar = fileInput.files[0]
  if (fileAvatar) {
    const reader = new window.FileReader()
    reader.addEventListener('load', () => {
      document.getElementById('imgModalAvatar').setAttribute('src', reader.result)
      /*
      La condition pour enlever modal-default-avatar & disabled
      est que l'image précédente est default avatar */
      // Remove class modal-default-avatar
      document.getElementById('imgModalAvatar').classList.remove('modal-default-avatar')
      // reactivate button delete
      document.getElementById('btnDelAvatar').removeAttribute('disabled')
      // *** Mettre la condition
    })
    reader.readAsDataURL(fileAvatar)
  }
})

// When close modal window for avatar
$('#avatarProfileModal').on('hide.bs.modal', () => {
  // if closed by validating and not by cancelling
  if (!btnCancel) {
    console.log('Je ferme la modale en validant')
    const imgModalAvatar = document.getElementById('imgModalAvatar').getAttribute('src')
  }
})

// ***** End of Handle avatar *****

// re-position the window when the modal to delete account is closed.
$('#deleteProfileModal').on('hidden.bs.modal', () => {
  // position at top of the page
  $('html,body').animate({ scrollTop: 0 }, 300)
})

/*  update the list of interest checked
    if action = R, remove the id from the list
    if action = A, add the if in the list

    arrayListInterest : list of id cheched in an array
    idToSearch : id to add or delete
    action : R=Remove, A=Add

    return the list updated
    Author : Frederic Parmentier
*/
function updateListInterest (listInterest, idToSearch, action) {
  let arrayListInterest = []
  if (listInterest.length > 0) {
    arrayListInterest = listInterest.split(';')
    let found = false
    let ind = -1
    while (!found && ind < arrayListInterest.length) {
      ind++
      if (arrayListInterest[ind] === idToSearch) {
        arrayListInterest.splice(ind, 1)
        found = true
      }
    }
  }
  if (action === 'A') { // add element
    arrayListInterest.push(idToSearch)
  }
  if (arrayListInterest.length === 1 && action === 'A') {
    return idToSearch
  } else {
    return arrayListInterest.join(';')
  }
}
