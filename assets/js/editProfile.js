// ***** manage interest choices *****
// initialize value of profile_listInterest

$('#deleteProfileModal').on('hidden.bs.modal', () => {
  $('#btn-profile-delete').trigger('focus', () => {
    // position at top of the page
    $('html,body').animate({ scrollTop: 0 }, 300)
  })
})
