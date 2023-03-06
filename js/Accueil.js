function objectClick(){
    document.getElementById('TweetObject').click()
}

function TweetButton(){
    if($('#TweetButton').css('opacity') == 1){
        var files = document.getElementById('TweetObject').files[0];
        var formData = new FormData()
        formData.append('file',files)
        formData.append('origin','tweeter')
        formData.append('message',$('#tweetText').val())
        console.log(formData)
        $.ajax({
            type:"POST",
            url:"../php/createTweet.php",
            processData : false,
            contentType: false,
            data: formData,
            success: function(output) {
                $('#tweetText').val('').empty()
                document.getElementById('close').click()
                document.getElementById('tweet').insertAdjacentHTML('afterbegin',output)
            }
        })
    }
}
function objectButton(){
    $('#preview').css('display','')
    $('#previewImage').attr("src",URL.createObjectURL(event.target.files[0]))
    tweetButtonOpacity()
}   
function tweetButtonOpacity(){
    if($('#tweetText').val() != '' || $('#preview').css('display') != 'none'){
        $('#TweetButton').css('opacity','1')
    }else{
        $('#TweetButton').css('opacity','0.6')
    }
}
$('#close').click(function(){
    $('#preview').css('display','none')
    $('#previewImage').attr("src",'none')
    var $el = $('#TweetObject');
    $el.wrap('<form>').closest(
    'form').get(0).reset();
    $el.unwrap();
    tweetButtonOpacity()
})
