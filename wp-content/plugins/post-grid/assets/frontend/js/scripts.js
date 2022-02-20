jQuery(document).ready(function($){

    $(document).on('submit', '.post-grid .post-grid-search forms', function(e){

        e.preventDefault();



        //var keyword = $(this).val();
        var grid_id = $(this).attr('grid_id');
        //var key = e.which;
        $('#post-grid-'+grid_id+' .search-loading').addClass('active');

        formData = $(this).serialize();

        // console.log(formData);
        // console.log(grid_id);


            var is_reset = 'no';
            //if(keyword.length>3){
                //$('#post-grid-'+grid_id+' .search-icon').html('<i class="fas fa-spin fa-spinner"></i>');

                //$('.pagination').fadeOut();

                $.ajax({
                    type: 'POST',
                    context: this,
                    url:post_grid_ajax.post_grid_ajaxurl,
                    data: {"action": "post_grid_ajax_search", "grid_id":grid_id,"is_reset":is_reset,"formData":formData,  },
                    success: function(response){

                        var datas = JSON.parse( response );
                        pagination = datas['pagination'];
                        html = datas['html'];
                        // console.log(html);
                        // console.log('No enter');
                        // console.log(pagination);
                        console.log('Hello');


                        $('#post-grid-'+grid_id+' .grid-items').html(html);
                        //$('#post-grid-'+grid_id+' .search-icon').html('<i class="fas fa-search"></i>');
                        $('#post-grid-'+grid_id+' .paginate').html(pagination);
                        $('#post-grid-'+grid_id+' .search-loading').removeClass('active');


                    }
                });
            //}

    })


});






