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

// Load image when modal window is opened
$('#avatarProfileModal').on('show.bs.modal', () => {
  document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('imgAvatarProfile').getAttribute('src'))
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
