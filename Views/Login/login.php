<style>
    .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .login-card {
        border-radius: 14px;
        box-shadow: 0 12px 30px rgba(23, 37, 84, 0.08);
        overflow: hidden;
        border: 0;
    }

    .brand-logo {
        display: block;
        max-width: 240px;
        width: 100%;
        height: auto;
        margin: 0 auto 18px auto;
        object-fit: contain;
    }

    .login-title {
        color: #1f2937;
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1.15rem;
    }

    .login-subtitle {
        color: #6b7280;
        margin-bottom: 18px;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: 30px;
        padding: 12px 18px;
        font-size: 1rem;
        box-shadow: none;
        border: 1px solid #d1d5db;
        background: #fff;
    }

    .form-control:focus {
        border-color: #295cff;
        box-shadow: 0 6px 18px rgba(41, 92, 255, 0.08);
        outline: none;
    }

    .btn-user {
        border-radius: 30px;
        padding: 12px 18px;
        font-weight: 600;
        font-size: 1rem;
    }

    .login-box {
        width: 100%;
        max-width: 520px;
        margin: 0 12px;
    }

    html,
    body {
        overflow-x: hidden;
    }

    .login-wrapper .container {
        max-width: 100%;
        padding-left: 12px;
        padding-right: 12px;
        box-sizing: border-box;
    }

    .login-box .card {
        width: 100%;
        box-sizing: border-box;
    }

    .login-box img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<script>
    function validateNumberInput(event) {
        var key = event.key;
        if (!/^[0-9]+$/.test(key)) {
            event.preventDefault();
        }
    }

    function validatePaste(event) {
        var paste = (event.clipboardData || window.clipboardData).getData('text');
        if (!/^[0-9]+$/.test(paste)) {
            event.preventDefault();
        }
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-9 login-box">
            <div class="card login-card o-hidden my-5 mx-auto">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="dropdown-item" id="showGeneralModal" href="#" data-toggle="modal" data-target="#generalModal" style="display:none"></a>
                            <div class="p-5 text-center">
                                <img src="<?php echo URL ?>Views/Default/img/logo-sahm.png" class="brand-logo" alt="SAHM">
                                <div class="login-title">Inducciones SST</div>

                                <form class="user needs-validation" id="formVinculacion" novalidate>
                                    <!-- Documento -->
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            id="documento"
                                            name="documento"
                                            class="form-control form-control-lg text-center"
                                            placeholder="Documento"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            onpaste="validatePaste(event)"
                                            autocomplete="off" required>
                                        <div class="invalid-feedback">Campo obligatorio.</div>
                                    </div>
                                    <!-- Toekn -->
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            id="token"
                                            name="token"
                                            class="form-control form-control-lg text-center"
                                            placeholder="Token de acceso"
                                            autocomplete="off" required>
                                        <div class="invalid-feedback">Campo obligatorio.</div>
                                    </div>
                                    <!-- Botón -->
                                    <button class="btn btn-primary btn-user btn-block btn-lg mt-3" type="submit">
                                        Ingresar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        // Validación de formularios (bootstrap style)
        $(".needs-validation").on("submit", function(event) {
            var form = this;
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                $(form).addClass('was-validated');
            } else {
                event.preventDefault();
                $(form).addClass('was-validated');

                let documento = $.trim($("#documento").val());
                let token = $.trim($("#token").val());

                $.ajax({
                    type: "POST",
                    url: "<?php echo URL; ?>Sst/validateUser",
                    dataType: "json",
                    data: {
                        documento : documento,
                        token: token
                    },
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href = "<?php echo URL; ?>Sst/gestion";
                        } else {
                            jQuery("#modalMessage").text('Acceso no permitido. Por favor, comuníquese con el área de Recursos Humanos.');
                            jQuery("#modalMessage").css({
                                'color': 'red'
                            });
                            jQuery("#showGeneralModal")[0].click();
                            $('#documento').val('');
                            $('#token').val('');
                        }
                    }
                });
            }
        });
    });
</script>