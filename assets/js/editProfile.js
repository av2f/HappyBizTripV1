// ***** Handle interest choices *****
// initialize value of edit_profile_listInterest
window.addEventListener('DOMContentLoaded', () => {
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
  // Define variables
  const defaultAvatar = 'defaultAvatar.png'
  const fileInput = document.getElementById('uploadFile')
  var fileAvatar
  var cropBoxData
  var canvasData
  var cropper
  // handle when show and hide modal
  // On show : atrribute image in modal body
  // on hide : empty the src of modal image and destroy cropper if exists
  $('#avatarProfileModal').on('shown.bs.modal', () => {
    document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('imgAvatarProfile').getAttribute('src'))
    // Activate delete button if deactivated
    if (!isButtonActivated(document.getElementById('btnDelAvatar'))) {
      document.getElementById('btnDelAvatar').removeAttribute('disabled')
    }
    // If image = default avatar, disable delete button and add class to resize default avatar
    if (document.getElementById('imgModalAvatar').getAttribute('src').includes(defaultAvatar)) {
      document.getElementById('btnDelAvatar').setAttribute('disabled', 'disabled')
      document.getElementById('imgModalAvatar').classList.add('modal-default-avatar')
    } else {
      cropper = new Cropper(document.getElementById('imgModalAvatar'), {
        // autoCropArea: 0.5,
        aspectRatio: 1,
        viewMode: 1,
        ready: function () {
          // Should set crop box data first here
          cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData)
        }
      })
    }
    // Reset input file value
    fileInput.value = ''
  }).on('hidden.bs.modal', () => {
    document.getElementById('imgModalAvatar').setAttribute('src', '')
    document.getElementById('uploadFile').value = ''
    console.log(cropper instanceof Cropper)
    if (cropper instanceof Cropper) {
      cropper.destroy()
      cropper = null
    }
    console.log('je ferme modal')
    console.log(cropper instanceof Cropper)
  })
  //
  /*  Click delete button on avatar modal windows
    1. Replace picture by default avatar
    2. add class 'modal-default-avatar' to reduce size of default avatar
    3. Disabled button delete
    4. reset the value of input file
    5. Destroy cropper if exists
  */
  document.getElementById('btnDelAvatar').addEventListener('click', () => {
    document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
    document.getElementById('imgModalAvatar').classList.add('modal-default-avatar')
    document.getElementById('btnDelAvatar').setAttribute('disabled', 'disabled')
    document.getElementById('uploadFile').value = ''
    if (cropper instanceof Cropper) {
      cropper.destroy()
      cropper = null
    }
  })

  // Click on button Modify
  document.getElementById('btnChangeAvatar').addEventListener('click', () => {
    document.getElementById('uploadFile').click()
  })

  // Handle change of picture
  fileInput.addEventListener('change', () => {
    fileAvatar = fileInput.files[0]
    if (fileAvatar) {
      const reader = new window.FileReader()
      reader.addEventListener('load', () => {
        const previousImage = document.getElementById('imgModalAvatar').getAttribute('src')
        document.getElementById('imgModalAvatar').setAttribute('src', reader.result)
        /* If previous image is default avatar
          Remove class modal-default-avatar and disabled option
        */
        if (previousImage.includes(defaultAvatar)) {
          document.getElementById('imgModalAvatar').classList.remove('modal-default-avatar')
          // reactivate button delete
          document.getElementById('btnDelAvatar').removeAttribute('disabled')
        }
      })
      reader.readAsDataURL(fileAvatar)
    }
  })
  // When click on Validate button
  document.getElementById('btnValidateAvatar').addEventListener('click', () => {
    console.log('je valide')
    const imgModalAvatar = document.getElementById('imgModalAvatar').getAttribute('src')
  })

  // ***** End of Handle avatar *****

  // re-position the window when the modal to delete account is closed.
  $('#deleteProfileModal').on('hidden.bs.modal', () => {
    // position at top of the page
    $('html,body').animate({ scrollTop: 0 }, 300)
  })
})

/*
    Update the list of interest checked
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

/*
  Check if button buttonToCheck is Activated
*/
function isButtonActivated (buttonToCheck) {
  let activated = true
  if (buttonToCheck.hasAttribute('disabled')) {
    activated = false
  }
  return activated
}
