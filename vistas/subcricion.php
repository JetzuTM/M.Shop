<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — MultiShop</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-image: url("../imagenes/fondoin.png");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            padding: 24px 16px;
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.58);
            backdrop-filter: blur(4px);
            z-index: 0;
        }

        /* ── Card ───────────────────────────────────────────────────── */
        .card-register {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            box-shadow: 0 28px 70px rgba(0,0,0,0.38), 0 0 0 1px rgba(255,255,255,0.25);
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 2;
            overflow: hidden;
            animation: slideUp 0.45s cubic-bezier(.16,1,.3,1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Header ─────────────────────────────────────────────────── */
        .card-header-reg {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 55%, #0f3460 100%);
            padding: 2rem 2.25rem 1.6rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header-reg::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .card-header-reg::after {
            content: '';
            position: absolute;
            bottom: -30px; left: -30px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .logo-img {
            width: 72px; height: 72px;
            object-fit: contain;
            display: block;
            margin: 0 auto 0.75rem;
            filter: drop-shadow(0 4px 14px rgba(0,0,0,0.45));
            position: relative; z-index: 1;
        }

        .card-header-reg h1 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            position: relative; z-index: 1;
        }

        .card-header-reg p {
            color: rgba(255,255,255,0.6);
            font-size: 0.82rem;
            margin-top: 0.25rem;
            position: relative; z-index: 1;
        }

        /* ── Body ───────────────────────────────────────────────────── */
        .card-body-reg { padding: 1.75rem 2.25rem 2rem; }

        /* ── Steps indicator ────────────────────────────────────────── */
        .steps-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 1.75rem;
        }

        .step-dot {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: #e8ecf0;
            color: #aaa;
            font-size: 0.78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .step-dot.active   { background: linear-gradient(135deg,#1a1a2e,#0f3460); color: #fff; box-shadow: 0 4px 14px rgba(15,52,96,0.35); }
        .step-dot.done     { background: #22c55e; color: #fff; }
        .step-dot.done i   { font-size: 0.75rem; }

        .step-line {
            flex: 1;
            height: 2px;
            background: #e8ecf0;
            max-width: 50px;
            transition: background 0.3s ease;
        }

        .step-line.done { background: #22c55e; }

        .step-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            margin-top: -10px;
        }

        .step-label {
            font-size: 0.7rem;
            color: #bbb;
            font-weight: 500;
            flex: 1;
            text-align: center;
        }

        .step-label.active { color: #0f3460; font-weight: 700; }

        /* ── Panels ─────────────────────────────────────────────────── */
        .form-step { display: none; }
        .form-step.active { display: block; animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Input groups ────────────────────────────────────────────── */
        .field-group { margin-bottom: 1.1rem; }
        .field-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.4rem;
        }

        .input-shell { position: relative; }

        .input-shell .icon-l,
        .input-shell .icon-r {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            pointer-events: none;
            color: #b0b7c3;
            transition: color 0.2s;
        }

        .input-shell .icon-l  { left: 14px; }
        .input-shell .icon-r  { right: 14px; pointer-events: all; cursor: pointer; }
        .input-shell .icon-r:hover { color: #0f3460; }

        .input-shell input,
        .input-shell select {
            width: 100%;
            padding: 0.72rem 2.7rem;
            border: 2px solid #e3e8ef;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            color: #1a202c;
            background: #f8f9fc;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-shell input:focus {
            border-color: #0f3460;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15,52,96,0.09);
        }

        .input-shell input.valid   { border-color: #22c55e; background: #fff; }
        .input-shell input.invalid { border-color: #ef4444; background: #fff; }
        .input-shell input.valid:focus   { box-shadow: 0 0 0 4px rgba(34,197,94,0.1); }
        .input-shell input.invalid:focus { box-shadow: 0 0 0 4px rgba(239,68,68,0.1); }

        .field-hint {
            font-size: 0.74rem;
            margin-top: 5px;
            min-height: 16px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .field-hint.ok  { color: #16a34a; }
        .field-hint.err { color: #dc2626; }
        .field-hint.tip { color: #94a3b8; }

        /* ── intl-tel-input overrides ──────────────────────────────── */
        .iti { width: 100%; }
        .iti__flag-box, .iti__dial-code { font-size: 0.85rem; }
        .iti__selected-flag { border-radius: 10px 0 0 10px; background: #f0f2f7; border-right: 1px solid #e3e8ef; }
        #phone { padding-left: 90px !important; }

        /* ── Password strength ───────────────────────────────────────── */
        .strength-wrap { margin-top: 8px; }

        .strength-bars {
            display: flex;
            gap: 5px;
            margin-bottom: 5px;
        }

        .sbar {
            flex: 1; height: 5px;
            border-radius: 3px;
            background: #e0e5ee;
            transition: background 0.35s ease;
        }

        .strength-text {
            font-size: 0.73rem;
            font-weight: 600;
            color: #aaa;
            margin-bottom: 6px;
        }

        .reqs-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 8px;
        }

        .req-item {
            font-size: 0.73rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s;
        }

        .req-item i { font-size: 0.65rem; }
        .req-item.met { color: #16a34a; }

        /* ── Navegación pasos ────────────────────────────────────────── */
        .btn-nav {
            display: flex;
            gap: 10px;
            margin-top: 1rem;
        }

        .btn-action {
            flex: 1;
            padding: 0.8rem 1rem;
            border: none;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all 0.3s ease;
        }

        .btn-action.primary {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            color: #fff;
        }

        .btn-action.primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(15,52,96,0.38);
        }

        .btn-action.secondary {
            background: #f0f2f7;
            color: #4a5568;
            flex: 0.45;
        }

        .btn-action.secondary:hover {
            background: #e2e6ee;
        }

        .btn-action:active { transform: translateY(0) !important; }
        .btn-action:disabled { opacity: 0.65; pointer-events: none; }

        /* ── Alert global ────────────────────────────────────────────── */
        #alertContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 340px;
        }

        .custom-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 0.84rem;
            font-weight: 500;
            box-shadow: 0 8px 30px rgba(0,0,0,0.18);
            animation: alertIn 0.3s cubic-bezier(.16,1,.3,1);
        }

        @keyframes alertIn {
            from { opacity:0; transform: translateX(20px); }
            to   { opacity:1; transform: translateX(0); }
        }

        .custom-alert.danger  { background:#fee2e2; color:#991b1b; border-left:4px solid #ef4444; }
        .custom-alert.success { background:#d1fae5; color:#065f46; border-left:4px solid #22c55e; }
        .custom-alert i { margin-top:2px; flex-shrink:0; }

        .btn-close-alert {
            background:none; border:none; cursor:pointer;
            margin-left:auto; color:inherit; opacity:0.6; padding:0;
        }

        /* ── Footer link ─────────────────────────────────────────────── */
        .card-footer-reg {
            text-align: center;
            padding: 1rem 2.25rem 1.5rem;
            font-size: 0.82rem;
            color: #888;
            border-top: 1px solid #f0f2f5;
        }

        .card-footer-reg a { color: #0f3460; font-weight: 600; text-decoration: none; }
        .card-footer-reg a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="bg-overlay"></div>

<!-- ── Alert container ────────────────────────────────────────────────── -->
<div id="alertContainer"></div>

<div class="card-register">

    <!-- ── Header ──────────────────────────────────────────────────────── -->
    <div class="card-header-reg">
        <img src="../public/img/Multi.png" class="logo-img" alt="MultiShop">
        <h1>Crear Cuenta</h1>
        <p>Únete a MultiShop — es gratis y rápido</p>
    </div>

    <!-- ── Body ────────────────────────────────────────────────────────── -->
    <div class="card-body-reg">

        <!-- Steps indicator -->
        <div class="steps-indicator">
            <div class="step-dot active" id="dot1">1</div>
            <div class="step-line"      id="line1"></div>
            <div class="step-dot"       id="dot2">2</div>
            <div class="step-line"      id="line2"></div>
            <div class="step-dot"       id="dot3">3</div>
        </div>
        <div class="step-labels">
            <span class="step-label active" id="lbl1">Datos personales</span>
            <span class="step-label"        id="lbl2">Contacto</span>
            <span class="step-label"        id="lbl3">Contraseña</span>
        </div>

        <form id="reg-form" action="../ajax/registro.php" method="post" onsubmit="return enviarFormulario(event)">

            <!-- ══ PASO 1: Datos personales ════════════════════════════ -->
            <div class="form-step active" id="step1">

                <div class="field-group">
                    <label for="name">Nombre completo</label>
                    <div class="input-shell">
                        <i class="fa-solid fa-user icon-l"></i>
                        <input type="text" id="name" name="name"
                               placeholder="Ej: Juan Pérez" autocomplete="name"
                               oninput="validateField('name')" required>
                        <i class="fa-solid fa-circle-check icon-r" id="icon-name" style="color:transparent"></i>
                    </div>
                    <span class="field-hint tip" id="hint-name">Solo letras y espacios, mínimo 3 caracteres</span>
                </div>

                <div class="field-group">
                    <label for="cedula">Número de cédula / documento</label>
                    <div class="input-shell">
                        <i class="fa-solid fa-id-card icon-l"></i>
                        <input type="text" id="cedula" name="cedula"
                               placeholder="Ej: 12345678" inputmode="numeric"
                               oninput="validateField('cedula')" required>
                        <i class="fa-solid fa-circle-check icon-r" id="icon-cedula" style="color:transparent"></i>
                    </div>
                    <span class="field-hint tip" id="hint-cedula">7 a 10 dígitos numéricos</span>
                </div>

                <div class="btn-nav">
                    <button type="button" class="btn-action primary" onclick="goStep(2)">
                        Continuar <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ══ PASO 2: Contacto ═════════════════════════════════════ -->
            <div class="form-step" id="step2">

                <div class="field-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-shell">
                        <i class="fa-solid fa-envelope icon-l"></i>
                        <input type="email" id="email" name="email"
                               placeholder="tucorreo@ejemplo.com" autocomplete="email"
                               oninput="validateField('email')" required>
                        <i class="fa-solid fa-circle-check icon-r" id="icon-email" style="color:transparent"></i>
                    </div>
                    <span class="field-hint tip" id="hint-email">Usarás este correo para iniciar sesión</span>
                </div>

                <div class="field-group">
                    <label for="phone">Número de teléfono</label>
                    <div class="input-shell">
                        <input type="tel" id="phone" name="phone"
                               placeholder="Ej: 04121234567"
                               oninput="validateField('phone')" required>
                    </div>
                    <span class="field-hint tip" id="hint-phone">Incluirá el código de país automáticamente</span>
                </div>

                <div class="btn-nav">
                    <button type="button" class="btn-action secondary" onclick="goStep(1)">
                        <i class="fa-solid fa-arrow-left"></i> Atrás
                    </button>
                    <button type="button" class="btn-action primary" onclick="goStep(3)">
                        Continuar <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ══ PASO 3: Contraseña ═══════════════════════════════════ -->
            <div class="form-step" id="step3">

                <div class="field-group">
                    <label for="password">Contraseña</label>
                    <div class="input-shell">
                        <i class="fa-solid fa-lock icon-l"></i>
                        <input type="password" id="password" name="password"
                               placeholder="Mínimo 8 caracteres" autocomplete="new-password"
                               oninput="onPasswordInput(this.value)" required minlength="8">
                        <i class="fa-solid fa-eye icon-r" id="toggle-pass" onclick="togglePass('password','toggle-pass')"></i>
                    </div>

                    <!-- Indicador de fuerza -->
                    <div class="strength-wrap" id="strength-wrap" style="display:none">
                        <div class="strength-bars">
                            <div class="sbar" id="sb1"></div>
                            <div class="sbar" id="sb2"></div>
                            <div class="sbar" id="sb3"></div>
                            <div class="sbar" id="sb4"></div>
                        </div>
                        <div class="strength-text" id="strength-text">—</div>
                        <div class="reqs-grid">
                            <div class="req-item" id="req-len">
                                <i class="fa-solid fa-circle-check"></i> 8+ caracteres
                            </div>
                            <div class="req-item" id="req-upper">
                                <i class="fa-solid fa-circle-check"></i> Mayúscula (A-Z)
                            </div>
                            <div class="req-item" id="req-num">
                                <i class="fa-solid fa-circle-check"></i> Número (0-9)
                            </div>
                            <div class="req-item" id="req-sym">
                                <i class="fa-solid fa-circle-check"></i> Símbolo (!@#...)
                            </div>
                        </div>
                    </div>
                    <span class="field-hint tip" id="hint-password" style="display:none"></span>
                </div>

                <div class="field-group">
                    <label for="confirm_password">Confirmar contraseña</label>
                    <div class="input-shell">
                        <i class="fa-solid fa-lock icon-l"></i>
                        <input type="password" id="confirm_password"
                               placeholder="Repite tu contraseña" autocomplete="new-password"
                               oninput="checkConfirm()" required>
                        <i class="fa-solid fa-eye icon-r" id="toggle-confirm" onclick="togglePass('confirm_password','toggle-confirm')"></i>
                    </div>
                    <span class="field-hint" id="hint-confirm"></span>
                </div>

                <div class="btn-nav">
                    <button type="button" class="btn-action secondary" onclick="goStep(2)">
                        <i class="fa-solid fa-arrow-left"></i> Atrás
                    </button>
                    <button type="submit" class="btn-action primary" id="btn-submit">
                        <i class="fa-solid fa-user-plus"></i> Crear Cuenta
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- ── Footer ──────────────────────────────────────────────────────── -->
    <div class="card-footer-reg">
        ¿Ya tienes cuenta? <a href="./inicio.php">Inicia sesión</a>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>

// ── intl-tel-input ────────────────────────────────────────────────────────────
const phoneInputField = document.getElementById('phone');
const phoneInput = window.intlTelInput(phoneInputField, {
    initialCountry: 've',
    preferredCountries: ['ve','co','es','us','mx'],
    separateDialCode: true,
    utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js'
});

// ── Paso actual ───────────────────────────────────────────────────────────────
let currentStep = 1;

function goStep(to) {
    // Validar el paso actual antes de avanzar
    if (to > currentStep) {
        if (!validateStep(currentStep)) return;
    }

    document.getElementById('step' + currentStep).classList.remove('active');
    document.getElementById('step' + to).classList.add('active');

    // Actualizar step dots
    for (let i = 1; i <= 3; i++) {
        const dot = document.getElementById('dot' + i);
        const lbl = document.getElementById('lbl' + i);
        dot.classList.remove('active','done');
        lbl.classList.remove('active');

        if (i < to) {
            dot.classList.add('done');
            dot.innerHTML = '<i class="fa-solid fa-check"></i>';
        } else if (i === to) {
            dot.classList.add('active');
            dot.textContent = i;
            lbl.classList.add('active');
        } else {
            dot.textContent = i;
        }

        if (i < 3) {
            document.getElementById('line' + i).classList.toggle('done', i < to);
        }
    }

    currentStep = to;
}

// ── Validar un paso completo ──────────────────────────────────────────────────
function validateStep(step) {
    if (step === 1) return validateField('name') & validateField('cedula');
    if (step === 2) return validateField('email') & validateField('phone');
    if (step === 3) {
        const pw = document.getElementById('password');
        const ok = pw.value.length >= 8 && /[A-Z]/.test(pw.value) && /\d/.test(pw.value);
        if (!ok) {
            mostrarAlerta('La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.','danger');
            return false;
        }
        if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
            mostrarAlerta('Las contraseñas no coinciden.','danger');
            return false;
        }
        return true;
    }
    return true;
}

// ── Validaciones individuales ─────────────────────────────────────────────────
function validateField(id) {
    if (id === 'name')   return validateName();
    if (id === 'cedula') return validateCedula();
    if (id === 'email')  return validateEmail();
    if (id === 'phone')  return validatePhone();
    return true;
}

function setFieldState(inputId, iconId, hintId, ok, msg, tip = false) {
    const input = document.getElementById(inputId);
    const icon  = iconId  ? document.getElementById(iconId)  : null;
    const hint  = hintId  ? document.getElementById(hintId)  : null;

    input.classList.remove('valid','invalid');
    if (ok === true)  { input.classList.add('valid');   if (icon) { icon.style.color='#22c55e'; icon.className='fa-solid fa-circle-check icon-r'; } }
    if (ok === false) { input.classList.add('invalid'); if (icon) { icon.style.color='#ef4444'; icon.className='fa-solid fa-circle-xmark icon-r'; } }
    if (ok === null)  { if (icon) icon.style.color = 'transparent'; }

    if (hint) {
        hint.textContent = msg;
        hint.className   = 'field-hint ' + (tip ? 'tip' : (ok ? 'ok' : 'err'));
    }
    return ok;
}

function validateName() {
    const v = document.getElementById('name').value.trim();
    if (!v)          return setFieldState('name','icon-name','hint-name', false, 'El nombre es obligatorio');
    if (v.length<3)  return setFieldState('name','icon-name','hint-name', false, 'Mínimo 3 caracteres');
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(v))
                     return setFieldState('name','icon-name','hint-name', false, 'Solo letras y espacios');
    return setFieldState('name','icon-name','hint-name', true, '✓ Nombre válido');
}

function validateCedula() {
    const v = document.getElementById('cedula').value.trim();
    if (!v)                   return setFieldState('cedula','icon-cedula','hint-cedula', false, 'La cédula es obligatoria');
    if (!/^\d+$/.test(v))     return setFieldState('cedula','icon-cedula','hint-cedula', false, 'Solo dígitos numéricos');
    if (v.length<7||v.length>10) return setFieldState('cedula','icon-cedula','hint-cedula', false, 'Debe tener entre 7 y 10 dígitos');
    return setFieldState('cedula','icon-cedula','hint-cedula', true, '✓ Cédula válida');
}

function validateEmail() {
    const v = document.getElementById('email').value.trim();
    if (!v) return setFieldState('email','icon-email','hint-email', false, 'El correo es obligatorio');
    const re = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
    if (!re.test(v)) return setFieldState('email','icon-email','hint-email', false, 'Formato de correo inválido');
    return setFieldState('email','icon-email','hint-email', true, '✓ Correo válido');
}

function validatePhone() {
    if (!phoneInput.isValidNumber()) {
        document.getElementById('hint-phone').textContent = 'Número de teléfono inválido';
        document.getElementById('hint-phone').className   = 'field-hint err';
        phoneInputField.classList.add('invalid');
        return false;
    }
    document.getElementById('hint-phone').textContent = '✓ Número válido';
    document.getElementById('hint-phone').className   = 'field-hint ok';
    phoneInputField.classList.remove('invalid');
    phoneInputField.classList.add('valid');
    return true;
}

// ── Indicador de fuerza ───────────────────────────────────────────────────────
function onPasswordInput(val) {
    const wrap = document.getElementById('strength-wrap');
    wrap.style.display = val.length ? 'block' : 'none';
    if (!val.length) return;

    const checks = {
        len:   val.length >= 8,
        upper: /[A-Z]/.test(val),
        num:   /\d/.test(val),
        sym:   /[^A-Za-z0-9]/.test(val),
    };

    Object.entries(checks).forEach(([k,v]) => {
        document.getElementById('req-' + k).classList.toggle('met', v);
    });

    const score  = Object.values(checks).filter(Boolean).length;
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Débil','Regular','Buena','Fuerte ✓'];
    const bars   = ['sb1','sb2','sb3','sb4'].map(id => document.getElementById(id));

    bars.forEach((b,i) => { b.style.background = i < score ? colors[score-1] : '#e0e5ee'; });

    const txt = document.getElementById('strength-text');
    txt.textContent  = score ? labels[score-1] : '—';
    txt.style.color  = score ? colors[score-1] : '#aaa';

    checkConfirm();
}

function checkConfirm() {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('confirm_password').value;
    const hint = document.getElementById('hint-confirm');
    const inp  = document.getElementById('confirm_password');

    if (!p2) { hint.textContent=''; inp.classList.remove('valid','invalid'); return; }
    if (p1 === p2) {
        inp.classList.add('valid'); inp.classList.remove('invalid');
        hint.textContent = '✓ Las contraseñas coinciden'; hint.className = 'field-hint ok';
    } else {
        inp.classList.add('invalid'); inp.classList.remove('valid');
        hint.textContent = 'Las contraseñas no coinciden'; hint.className = 'field-hint err';
    }
}

// ── Mostrar/ocultar contraseña ────────────────────────────────────────────────
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye','fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash','fa-eye');
    }
}

// ── Alertas ───────────────────────────────────────────────────────────────────
function mostrarAlerta(mensaje, tipo) {
    const container = document.getElementById('alertContainer');
    const id = 'alert-' + Date.now();
    const iconMap = { danger:'fa-circle-exclamation', success:'fa-circle-check' };
    const div = document.createElement('div');
    div.id = id;
    div.className = 'custom-alert ' + tipo;
    div.innerHTML = `
        <i class="fa-solid ${iconMap[tipo]||'fa-circle-info'}"></i>
        <span>${mensaje}</span>
        <button class="btn-close-alert" onclick="document.getElementById('${id}').remove()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;
    container.appendChild(div);
    setTimeout(() => { div.style.opacity='0'; div.style.transition='opacity 0.4s'; setTimeout(()=>div.remove(),400); }, 5000);
}

// ── Envío del formulario ──────────────────────────────────────────────────────
function enviarFormulario(e) {
    e.preventDefault();
    if (!validateStep(3)) return false;

    // Guardar teléfono en formato E.164
    document.getElementById('phone').value = phoneInput.getNumber();

    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creando cuenta...';

    const form = document.getElementById('reg-form');
    const data = new FormData(form);

    fetch('../ajax/registro.php', { method:'POST', body: data })
    .then(r => r.text())
    .then(resp => {
        if (resp.includes('exitoso') || resp.includes('success') || resp.trim() === '1') {
            mostrarAlerta('¡Cuenta creada con éxito! Redirigiendo...','success');
            setTimeout(() => { window.location.href = './inicio.php'; }, 2000);
        } else {
            mostrarAlerta(resp || 'Ocurrió un error. Inténtalo de nuevo.','danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-user-plus"></i> Crear Cuenta';
        }
    })
    .catch(() => {
        mostrarAlerta('Error de conexión. Inténtalo de nuevo.','danger');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-user-plus"></i> Crear Cuenta';
    });

    return false;
}

</script>
</body>
</html>