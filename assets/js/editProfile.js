// import axios
import axios from 'axios'
// Import cropper
import Cropper from 'cropperjs'

// ***** Handle interest choices *****
// initialize value of edit_profile_listInterest
window.addEventListener('DOMContentLoaded', () => {
  // Define variables for handle of interests
  var arrayListInterest = []
  // Define variables for handle of avatar
  const defaultAvatar = 'defaultAvatar.png'
  const fileInput = document.getElementById('uploadFile')
  const modalImg = document.getElementById('imgModalAvatar')
  var fileAvatar
  var cropBoxData
  var canvasData
  var cropper
  //
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
  // handle when show and hide modal
  // On show : atrribute image in modal body
  // On shown : handle Cropper
  // on hidden : empty the src of modal image and destroy cropper if exists
  $('#avatarProfileModal').on('show.bs.modal', () => {
    modalImg.setAttribute('src', document.getElementById('imgAvatarProfile').getAttribute('src'))
    // Handle delete & validate buttons. Deactivate if defaultAvatar, else activate them if deactivated
    if (modalImg.getAttribute('src').includes(defaultAvatar)) {
      document.getElementById('btnDelAvatar').setAttribute('disabled', 'disabled')
      document.getElementById('btnValidateAvatar').setAttribute('disabled', 'disabled')
      modalImg.classList.add('modal-default-avatar')
    } else {
      if (!isButtonActivated(document.getElementById('btnDelAvatar'))) {
        document.getElementById('btnDelAvatar').removeAttribute('disabled')
      }
      if (!isButtonActivated(document.getElementById('btnValidateAvatar'))) {
        document.getElementById('btnValidateAvatar').removeAttribute('disabled')
      }
    }
  }).on('shown.bs.modal', () => {
    // If image = default avatar, disable delete button and add class to resize default avatar
    if (!modalImg.getAttribute('src').includes(defaultAvatar)) {
      cropper = createCropper(modalImg, cropBoxData, canvasData)
    }
    // Reset input file value
    fileInput.value = ''
  }).on('hidden.bs.modal', () => {
    // re-initialize data
    if (cropper instanceof Cropper) {
      cropper.destroy()
      cropper = null
    }
    modalImg.classList.remove('modal-default-avatar')
    modalImg.setAttribute('src', '')
  })
  //
  /*  Click delete button on avatar modal windows
    1. Replace picture by default avatar
    2. add class 'modal-default-avatar' to reduce size of default avatar
    3. Disable button delete
    4. reset the value of input file
    5. Destroy cropper if exists
  */
  document.getElementById('btnDelAvatar').addEventListener('click', () => {
    if (cropper instanceof Cropper) {
      cropper.destroy()
      cropper = null
    }
    modalImg.setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
    modalImg.classList.add('modal-default-avatar')
    document.getElementById('btnDelAvatar').setAttribute('disabled', 'disabled')
    fileInput.value = ''
  })

  // Click on button Modify
  document.getElementById('btnChangeAvatar').addEventListener('click', () => {
    fileInput.click()
  })

  // Handle change of picture
  fileInput.addEventListener('change', () => {
    if (cropper instanceof Cropper) { cropper.reset() }
    fileAvatar = fileInput.files[0]
    if (fileAvatar) {
      const reader = new window.FileReader()
      reader.addEventListener('load', () => {
        const previousImage = modalImg.getAttribute('src')
        modalImg.setAttribute('src', reader.result)
        if (cropper instanceof Cropper) {
          cropper.replace(reader.result)
        } else {
          cropper = createCropper(modalImg, cropBoxData, canvasData)
        }
        if (previousImage.includes(defaultAvatar)) {
          modalImg.classList.remove('modal-default-avatar')
          // Reactivate buttons delete & Validate
          document.getElementById('btnDelAvatar').removeAttribute('disabled')
          document.getElementById('btnValidateAvatar').removeAttribute('disabled')
        }
      })
      reader.readAsDataURL(fileAvatar)
    }
  })
  // When click on Validate button
  document.getElementById('btnValidateAvatar').addEventListener('click', () => {
    handleLoader(true)
    const inputUpdateAvatar = document.getElementById('input-update-avatar') // Token
    var msgError = ''
    const formData = new FormData()
    formData.append('_token', inputUpdateAvatar.dataset.token)
    // if Default avatar
    if (modalImg.getAttribute('src').includes(defaultAvatar)) {
      // delete the current avatar and replace by the default
      axios({
        method: 'post',
        url: inputUpdateAvatar.getAttribute('data-delurl'),
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          enctype: 'multipart/form-data'
        },
        data: formData
      })
        .then(function (response) {
          if (response.data.success) {
            // change menu and profile avatars
            document.getElementById('imgAvatarNav').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
            document.getElementById('imgAvatarProfile').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
          } else {
            switch (response.data.error) {
              case '2':
                msgError = document.getElementById('msgerr').dataset.errone
                break
              default:
                msgError = document.getElementById('msgerr').dataset.errone
                break
            }
            console.log(msgError)
            // flashy error message
          }
        })
        .catch(function (error) {
          // flashy error
          console.log(error)
        })
    // if new CroppedCanvas
    } else {
      const cropperImage = cropper.getCroppedCanvas()
      cropperImage.toBlob(function (blob) {
        formData.append('image', blob)
        axios({
          method: 'post',
          url: inputUpdateAvatar.getAttribute('data-ref'),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            enctype: 'multipart/form-data'
          },
          data: formData
        })
          .then(function (response) {
            if (response.data.success) {
              // Replace menu and profile image by the new
              const newImage = document.getElementById('pathavatar').dataset.path + response.data.success
              document.getElementById('imgAvatarNav').setAttribute('src', newImage)
              document.getElementById('imgAvatarProfile').setAttribute('src', newImage)
            } else {
              switch (response.data.error) {
                case '2':
                  msgError = document.getElementById('msgerr').dataset.errone
                  break
                case '3':
                  msgError = document.getElementById('msgerr').dataset.errtwo
                  break
                default:
                  msgError = document.getElementById('msgerr').dataset.errone
                  break
              }
              console.log(msgError)
              // Flashy error message
            }
          })
          .catch(function (error) {
            // flashy error
            console.log(error)
          })
      }, 'image/jpeg', 1)
    }
    handleLoader(false)
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

/*
  Create a new cropper instance
  with options.
*/
function createCropper (picture, cropBox, canvas) {
  const crop = new Cropper(picture, {
    // autoCropArea: 0.5,
    movable: false,
    zoomable: false,
    rotatable: false,
    scalable: false,
    viewMode: 3,
    ready: function () {
      // Should set crop box data first here
      crop.setCropBoxData(cropBox).setCanvasData(canvas)
    }
  })
  return crop
}

/*
  Activate or deactivate the load spinner
*/
function handleLoader (activate) {
  if (activate) {
    document.querySelector('.load-spinner').setAttribute('id', 'loader')
    document.getElementById('spinner').style.display = 'block'
  } else {
    document.querySelector('.load-spinner').setAttribute('id', 'unload')
    document.getElementById('spinner').style.display = 'none'
  }
}
