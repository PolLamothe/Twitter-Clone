var navSide = document.getElementById('navSide')
$('#editButton').click(function(){
    $('#mainContent').css('overflow','visible')
    $('body').css('overflow-y','hidden')
    $('#editInterface').css('display','inline')
    $('#editInterface').css('margin-left',-navSide.offsetWidth)
})

$('#camera').click(function(){
    $('#bannerInput').click()
})

function cameraChange(){
    $('#banner2').attr("src",URL.createObjectURL(event.target.files[0]))
}
function cameraChange2(){
    $('#profile_picture2').attr("src",URL.createObjectURL(event.target.files[0]))
}

$('#camera2').click(function(){
    $('#profileInput').click()
})

$('#submit').click(function(){
    var bannerImage = document.getElementById('bannerInput').files[0];
    var profilePictureImage = document.getElementById('profileInput').files[0];
    var formData = new FormData()
    formData.append('bannerImage',bannerImage)
    formData.append('profilePictureImage',profilePictureImage)
    formData.append('biographie',$('#biographieInput').val())
 
    $.ajax({
        type:'POST',
        url:'../php/submitChange.php',
        processData : false,
        contentType: false,
        data: formData,
        success: function(output){
            $('#editInterface').css('display','none')
            $('body').css('overflow-y','visible')
            $('#banner').attr('src',$('#banner2').attr("src"))
            $('#profile_picture').attr('src',$('#profile_picture2').attr("src"))
        }
    })
})

$('#exitCross').click(function(){
    $('#editInterface').css('display','none')
    $('body').css('overflow-y','visible')
})