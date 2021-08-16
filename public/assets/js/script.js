$(document).ready(function () {
    $('.header_info .person_info img').click(function () {
        $('body').toggleClass('header_change');
    });

    $('.__close__onboarding').click(function () {
        $(".__hide__onboarding").addClass('hide');
    });

    $(".__submit_on_key_press").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $(".__submit_search_form").submit();
        }
    });

    /* $('input[name="transport"]').click(function () {
         console.log("working");
         if ($(this).attr("value") == 2) {
             $(".__toggle__truck_option").addClass('hide').prop('required', false);
         } else {
             $(".__toggle__truck_option").removeClass('hide').prop('required', true);
         }
     });*/

    $('.dashboard_main .notification_title .notification_title .your_notification').click(function () {
        $('.dashboard_main .notification_title .notification_title p').removeClass('show');
        $(this).addClass('show');
        $('.dashboard_main .notification_title .nofication_content .notification_content_section.new_notification').removeClass('show');
        $('.dashboard_main .notification_title .nofication_content .notification_content_section.your_notification').addClass('show');
    });

    $('.dashboard_main .notification_title .notification_title .new_notification').click(function () {
        $('.dashboard_main .notification_title .notification_title p').removeClass('show');
        $(this).addClass('show');
        $('.dashboard_main .notification_title .nofication_content .notification_content_section.new_notification').addClass('show');
        $('.dashboard_main .notification_title .nofication_content .notification_content_section.your_notification').removeClass('show');
    });

    $(document).on('click', ".job_accordine p", function () {
        $('.job_popup .job_accordine .job_description').slideUp();
        $(this).next().slideDown();
        $('.job_popup .job_accordine p').removeClass('already_slide');
        $(this).addClass('already_slide');
    });

    $(document).on('click', ".kt_model_common__accept_job .modal-dialog .modal-content .close_icon", function () {
        $('#acceptJobModal').hide();
        $('.modal-backdrop').hide();
    });

    $('.account_page_body .__change_email_button').click(function () {
        $('.account_page_body .__change_email_input').slideUp();
        $(this).next().slideDown();
        $('.account_page_body .change').removeClass('active');
        $(this).addClass('active');
    });

    $('.account_page_body .__change_password_button').click(function () {
        $('.account_page_body .__change_password_input').slideUp();
        $('.account_page_body .__change_password_input').show();
        $(this).next().slideDown();
        $('.account_page_body .change').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('click', ".kt_model_common__decline_job .modal-dialog .modal-content .close_icon", function () {
        $('#declineJobModal').hide();
        $('.modal-backdrop').hide();
    });

    $(document).on('load', ".dataTables_empty", function () {
        $(this).parent().addClass('remove_hover');
    });

    $(document).on('click', ".job_popup_content .close_icon", function () {
        $('.job_popup').hide();
    });

    $('.header_info .header_right_section .notifications').click(function () {
        $('.dashboard_main').addClass('notification_open');
    });

    $('.dashboard_main .notification_title .notification_close img').click(function () {
        $('.dashboard_main').removeClass('notification_open');
    });

    if ($('input').val()) {
        $(this).addClass('new');
    }

    $('.lane_page_content .kt-portlet__head .lane_checkbox_content_transport').click(function(){
        $('.lane_page_content .kt-portlet__head .lane_checkbox_content_transport').removeClass('active');
        $(this).addClass('active');
        $('input[name="transport"]').prop('checked', false);
        $(this).find('input[name="transport"]').prop('checked', true);
        let radioVal = $(this).find('input[name="transport"]').attr("value");
        console.log(radioVal, "working");
        if (radioVal == 2) {
            $(".__toggle__truck_option").addClass('d-none').find('select[name="truck_id"]').prop('required', false);
        } else {
            $(".__toggle__truck_option").removeClass('d-none').find('select[name="truck_id"]').prop('required', true);
        }
    })

    $('.lane_page_content .kt-portlet__head .lane_checkbox_content_pricing').click(function(){
        $('.lane_page_content .kt-portlet__head .lane_checkbox_content_pricing').removeClass('active');
        $(this).addClass('active');
    })

    $('.day_section .day .local_circle').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).find('input[name="weekdays[]"]').prop('checked', false);
            $(this).find('input[name="pickup_days[]"]').prop('checked', false);
        } else {
            $(this).addClass('active');
            $(this).find('input[name="weekdays[]"]').prop('checked', true);
            $(this).find('input[name="pickup_days[]"]').prop('checked', true);
        }
    });

    $('.sidebar_section .kt-aside-menu .kt-menu__nav > li').addClass('mob_slide');
    $('.sidebar_section .kt-aside-menu .kt-menu__nav > li.active').removeClass('mob_slide');
    $('.menu_expand').click(function(){
        $('.sidebar_section .kt-aside-menu .kt-menu__nav > li.mob_slide').slideToggle();
        $('#kt_aside').toggleClass('sidebar_fix');
        $('body').toggleClass('body_fix');
    });

    $('.__lane_pricing_muval').click(function () {
        let pricingVal = $(this).find("input[type='radio']").val();
        $(".__lane_pricing_muval").find("input[type='radio']").attr("checked", false)
        $(this).find("input[type='radio']").attr("checked", true);
        if (pricingVal == 'tiered'){
            $('.__lane_single_pricing').removeClass('show').addClass('hide');
            $('.__lane_tiered_pricing').removeClass('hide').addClass('show');
            $('.range_section').removeClass('hide').addClass('show');
            $("#min_price").attr("required", false);
            $(".hide_show_range").attr("required", true);
            /*$(".tiered_price_class").attr("required", true);
            $(".range_end").attr("required", true);*/
        }else{
            $('.__lane_single_pricing').removeClass('hide').addClass('show');
            $('.__lane_tiered_pricing').removeClass('show').addClass('hide');
            $('.range_section').removeClass('show').addClass('hide');
            $("#min_price").attr("required", true);
            $(".hide_show_range").attr("required", false);
            /*$(".tiered_price_class").attr("required", false);
            $(".range_end").attr("required", false);*/
        }
    });


    $('.__ancillary__type__toggle').on('change', function() {
        ancillaryTypeManage();
    });

    $(document).on('click', ".__delivery_next_day", function () {
        $('.__transit_time_within').val(1);
    });
});

function ancillaryTypeManage(){
    let selectedText = $('.__ancillary__type__toggle').find("option:selected").text().toLowerCase();
    $(".__common_ancillaries").addClass('hide');
    if(selectedText == 'insurance'){
        $(".__toggle__insurance").removeClass('hide');
    }
    if(selectedText == 'car transport'){
        $(".__toggle__car__transport").removeClass('hide');
    }
    if(selectedText == 'packing' || selectedText == 'unpacking'){
        $(".__toggle__packaging").removeClass('hide');
    }
    if(selectedText == 'cleaning'){
        $(".__toggle__cleaning").removeClass('hide');
    }
    if(selectedText == 'materials'){

    }
}

$(function () {

    // $('.commonDatepicker').datepicker({format: 'yyyy-mm-dd'});

    // $('.js-bs-select2').select2();

    $('.header_info .header_right_section .notifications').click(function () {
        $('.dashboard_main').addClass('notification_open');
    });

    $('.dashboard_main .notification_title .notification_close img').click(function () {
        $('.dashboard_main').removeClass('notification_open');
    });


    $(document).on('click', ".__notification__seen", function (event) {

        event.preventDefault();
        let url = $(this).data("url");

        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $(".__notification__count").html(0);
            },
            error: function (data) {
                alert("Something Went Wrong!");
            }
        });
    });

    $('.dataTables_empty').addClass('remove_hover');
});

