$(document).ready(function () {
    $('.accordine_icon').click(function () {
        if (!$(this).parent().hasClass('open')) {
            $('.accordine .accordine_icon').parent().removeClass('open');
            $(this).parent().addClass('open');
        }
    });

    $('.move-to-registration').click(function () {

        let form = $("#company-registration");
        form.validate({
            rules: {
                name: {
                    required: true,
                },
                abn: {
                    required: true,
                },
                address: {
                    required: true,
                },
                website: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Company Name required",
                },
                abn: {
                    required: "ABN required",
                },
                address: {
                    required: "Company Address required",
                },
                website: {
                    required: "Company Website required",
                },
            },
            errorPlacement: function(error, element) {
                let placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

        if (form.valid() == true) {
            if (!$(this).parent().parent().next().hasClass('open')) {
                $('.accordine .accordine_icon').parent().removeClass('open');
                $(this).parent().parent().next().addClass('open');
            }
        }
    });

    $('.move-to-contact').click(function () {

        let formNew = $("#company-registration");
        formNew.validate({
            rules: {
                user_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
               /* password: {
                    required: true,
                    minlength : 5,
                },
                _verify_password: {
                    required: true,
                    minlength : 5,
                    equalTo : "#password"
                },*/
            },
            messages: {
                user_name: {
                    required: "Username required",
                },
                email: {
                    required: "Email required",
                    email: "Please enter a valid email address",
                },
              /*  password: {
                    required: "Password required",
                    minlength: "Your Password must consist of at least 5 characters",
                },
                _verify_password: {
                    required: "Re-entered Password",
                    minlength: "Your Password must consist of at least 5 characters",
                    equalTo: "Please enter the same password",
                },*/
            },
        });

        if (formNew.valid() == true) {
            if (!$(this).parent().parent().next().hasClass('open')) {
                $('.accordine .accordine_icon').parent().removeClass('open');
                $(this).parent().parent().next().addClass('open');
            }
        }
    });
});
