$('#add-image').click(function(){
        
    const index = +$('#widget_counter').val()
    //je recupere le prototype du code html a generer
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g,index)
    //j'ajoute mon template
    $('#ad_images').append(tmpl)
    $('#widget_counter').val(index + 1)

    handleRemove()

})
function handleRemove(){
    const btns = $("button[data-action='delete']").click(function(){
        const id = this.dataset.target
        $(id).remove()      
    }) 

}
function updateCounter(){
    const count = +$('#ad_images div.form-group').length
    $('#widget_counter').val(count)
}
updateCounter()
handleRemove()
