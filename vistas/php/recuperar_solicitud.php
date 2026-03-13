<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Cuenta — MultiShop</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../public/img/Multi.png">
    <link rel="shortcut icon" href="../public/img/Multi.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-image: url("../../imagenes/fondoin.png");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(3px);
            z-index: 0;
        }

        .card-recovery {
            background: rgba(255,255,255,0.97);
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.3);
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 2;
            overflow: hidden;
            animation: slideUp 0.4s cubic-bezier(.16,1,.3,1);
        }

        @keyframes slideUp {
            from { opacity:0; transform:translateY(30px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .card-header-custom {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            position: relative;
        }

        .logo {
            width: 70px;
            height: 70px;
            background-image: url("../../public/img/Multi.png");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            display: block;
            margin: 0 auto 0.75rem;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4));
        }

        .card-header-custom h1 {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .card-header-custom p {
            color: rgba(255,255,255,0.65);
            font-size: 0.82rem;
            margin-top: 0.25rem;
        }

        .card-body-custom { padding: 1.75rem 2rem 2rem; }

        /* ── Método tabs ── */
        .method-tabs {
            display: flex;
            background: #f0f2f5;
            border-radius: 14px;
            padding: 4px;
            gap: 3px;
            margin-bottom: 1.5rem;
        }

        .method-tab {
            flex: 1;
            border: none;
            background: transparent;
            border-radius: 11px;
            padding: 0.6rem 0.4rem;
            font-family: 'Poppins', sans-serif;
            font-size: 0.76rem;
            font-weight: 500;
            color: #777;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .method-tab i { font-size: 1.1rem; }

        .method-tab.active {
            background: #fff;
            color: #0f3460;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
            font-weight: 600;
        }

        .method-tab:hover:not(.active) { color: #444; background: rgba(255,255,255,0.6); }

        /* ── Forms ── */
        .form-panel { display: none; animation: fadeIn 0.3s ease; }
        .form-panel.active { display: block; }

        @keyframes fadeIn {
            from { opacity:0; transform:translateY(6px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group-custom label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.4rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2.6rem;
            border: 2px solid #e8ecf0;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            color: #222;
            background: #fafbfc;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: #0f3460;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15,52,96,0.08);
        }

        .input-wrapper input:focus + i,
        .input-wrapper i:has(+ input:focus) { color: #0f3460; }

        .input-wrapper input:focus ~ i { color: #0f3460; }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #0d0d1a, #0a2344);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(15,52,96,0.4);
        }

        .btn-submit:active { transform: translateY(0); }

        .btn-submit.loading { pointer-events: none; opacity: 0.8; }

        .btn-back-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.82rem;
            color: #888;
            text-decoration: none;
        }

        .btn-back-link:hover { color: #0f3460; }
        .btn-back-link i { margin-right: 4px; }

        /* ── Alerta de estado ── */
        .status-alert {
            display: none;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1rem;
            align-items: flex-start;
            gap: 10px;
        }

        .status-alert.show { display: flex; }
        .status-alert.success { background: #d1fae5; color: #065f46; border: 1.5px solid #6ee7b7; }
        .status-alert.error   { background: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5; }
        .status-alert.info    { background: #dbeafe; color: #1e40af; border: 1.5px solid #93c5fd; }
        .status-alert i { margin-top: 2px; flex-shrink: 0; }

        /* ── PASO 2: Ingreso de OTP ── */
        #step-otp { display: none; animation: fadeIn 0.35s ease; }
        #step-otp.active { display: block; }

        .otp-info {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .otp-info .otp-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .otp-info .otp-icon i { color: #fff; font-size: 1.6rem; }

        .otp-info h3 { font-size: 1.1rem; font-weight: 700; color: #1a1a2e; }
        .otp-info p  { font-size: 0.82rem; color: #666; margin-top: 0.3rem; line-height: 1.4; }
        .otp-info span.highlight { font-weight: 600; color: #0f3460; }

        .otp-boxes {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .otp-box {
            width: 52px;
            height: 58px;
            border: 2px solid #e0e5ee;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            color: #0f3460;
            background: #f8f9fc;
            transition: all 0.2s ease;
            outline: none;
            font-family: 'Poppins', sans-serif;
        }

        .otp-box:focus {
            border-color: #0f3460;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15,52,96,0.1);
        }

        .otp-box.filled { border-color: #0f3460; background: #fff; }

        .otp-timer {
            text-align: center;
            font-size: 0.8rem;
            color: #999;
            margin-bottom: 1rem;
        }

        .otp-timer span { color: #0f3460; font-weight: 600; }

        .btn-resend {
            background: none;
            border: none;
            color: #0f3460;
            font-family: 'Poppins', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            display: none;
        }

        .btn-resend:hover { color: #1a1a2e; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.25rem 0;
            color: #ccc;
            font-size: 0.78rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eee;
        }
    </style>
</head>
<body>
<div class="bg-overlay"></div>

<div class="card-recovery">

    <!-- Header -->
    <div class="card-header-custom">
        <span class="logo"></span>
        <h1>Recuperar Cuenta</h1>
        <p>Selecciona cómo quieres verificar tu identidad</p>
    </div>

    <div class="card-body-custom">

        <!-- ═══════════════════════════════════ PASO 1 ═══════════════════ -->
        <div id="step-form">

            <!-- Alerta de estado -->
            <div class="status-alert" id="alert-box">
                <i class="fa-solid fa-circle-info"></i>
                <span id="alert-text"></span>
            </div>

            <!-- Tabs de métodos -->
            <div class="method-tabs" role="tablist">
                <button class="method-tab active" id="tab-email" onclick="switchTab('email')" type="button">
                    <i class="fa-solid fa-envelope"></i>
                    Correo
                </button>
                <button class="method-tab" id="tab-cedula" onclick="switchTab('cedula')" type="button">
                    <i class="fa-solid fa-id-card"></i>
                    Cédula
                </button>
                <button class="method-tab" id="tab-telefono" onclick="switchTab('telefono')" type="button">
                    <i class="fa-solid fa-phone"></i>
                    Teléfono
                </button>
            </div>

            <!-- Panel: Correo -->
            <div class="form-panel active" id="panel-email">
                <form id="form-email" onsubmit="enviarRecuperacion(event,'email')">
                    <div class="input-group-custom">
                        <label for="input-email">Correo electrónico registrado</label>
                        <div class="input-wrapper">
                            <input type="email" id="input-email" name="valor"
                                   placeholder="tucorreo@ejemplo.com" required autocomplete="email">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" id="btn-email">
                        <i class="fa-solid fa-paper-plane"></i>
                        Enviar Código
                    </button>
                </form>
            </div>

            <!-- Panel: Cédula -->
            <div class="form-panel" id="panel-cedula">
                <form id="form-cedula" onsubmit="enviarRecuperacion(event,'cedula')">
                    <div class="input-group-custom">
                        <label for="input-cedula">Número de cédula o documento</label>
                        <div class="input-wrapper">
                            <input type="text" id="input-cedula" name="valor"
                                   placeholder="Ej: 12345678" inputmode="numeric" required>
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" id="btn-cedula">
                        <i class="fa-solid fa-paper-plane"></i>
                        Enviar Código
                    </button>
                </form>
            </div>

            <!-- Panel: Teléfono -->
            <div class="form-panel" id="panel-telefono">
                <form id="form-telefono" onsubmit="enviarRecuperacion(event,'telefono')">
                    <div class="input-group-custom">
                        <label for="input-telefono">Número de teléfono registrado</label>
                        <div class="input-wrapper">
                            <input type="tel" id="input-telefono" name="valor"
                                   placeholder="Ej: 04121234567" required>
                            <i class="fa-solid fa-phone"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" id="btn-telefono">
                        <i class="fa-solid fa-paper-plane"></i>
                        Enviar Código
                    </button>
                </form>
            </div>

            <a href="../inicio.php" class="btn-back-link">
                <i class="fa-solid fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
        <!-- ═══════════════════════════════════════════════════════════════ -->

        <!-- ═══════════════════════════════════ PASO 2: OTP ══════════════ -->
        <div id="step-otp">
            <div class="otp-info">
                <div class="otp-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>Código de verificación</h3>
                <p>Enviamos un código de 6 dígitos a<br><span class="highlight" id="otp-destino"></span></p>
            </div>

            <!-- Alerta OTP -->
            <div class="status-alert" id="otp-alert-box">
                <i class="fa-solid fa-circle-info"></i>
                <span id="otp-alert-text"></span>
            </div>

            <div class="otp-boxes">
                <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" id="otp1">
                <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" id="otp2">
                <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" id="otp3">
                <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" id="otp4">
                <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" id="otp5">
                <input class="otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" id="otp6">
            </div>

            <div class="otp-timer">
                El código expira en <span id="timer-countdown">15:00</span>
                <br>
                <button class="btn-resend" id="btn-resend" onclick="reenviarCodigo()">
                    Reenviar código
                </button>
            </div>

            <button class="btn-submit" id="btn-verificar" onclick="verificarOTP()">
                <i class="fa-solid fa-check-circle"></i>
                Verificar Código
            </button>

            <div class="divider">o</div>

            <button type="button" class="btn-submit" onclick="volverAlPaso1()"
                    style="background:linear-gradient(135deg,#6b7280,#4b5563);">
                <i class="fa-solid fa-arrow-left"></i>
                Cambiar método de recuperación
            </button>
        </div>
        <!-- ═══════════════════════════════════════════════════════════════ -->

    </div>
</div>

<script>
// ── Estado ──────────────────────────────────────────────────────────────────
let metodoActual = 'email';
let correoDestino = '';
let timerInterval = null;
let timerSeconds = 900; // 15 min

// ── Tabs ─────────────────────────────────────────────────────────────────────
function switchTab(method) {
    metodoActual = method;
    document.querySelectorAll('.method-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + method).classList.add('active');
    document.getElementById('panel-' + method).classList.add('active');
    ocultarAlerta('alert-box');
}

// ── Alerta helper ─────────────────────────────────────────────────────────────
function mostrarAlerta(boxId, textId, tipo, mensaje) {
    const box  = document.getElementById(boxId);
    const text = document.getElementById(textId);
    const icon = box.querySelector('i');
    box.className = 'status-alert show ' + tipo;
    text.textContent = mensaje;
    const iconMap = { success:'fa-circle-check', error:'fa-circle-exclamation', info:'fa-circle-info' };
    icon.className = 'fa-solid ' + (iconMap[tipo] || 'fa-circle-info');
}

function ocultarAlerta(boxId) {
    document.getElementById(boxId).className = 'status-alert';
}

// ── Enviar solicitud de recuperación ─────────────────────────────────────────
function enviarRecuperacion(e, metodo) {
    e.preventDefault();
    const valor = document.getElementById('input-' + metodo).value.trim();
    const btn   = document.getElementById('btn-' + metodo);

    if (!valor) return;

    btn.classList.add('loading');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enviando...';
    ocultarAlerta('alert-box');

    fetch('./enviar_correo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'metodo=' + encodeURIComponent(metodo) + '&valor=' + encodeURIComponent(valor)
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Enviar Código';

        if (data.success) {
            correoDestino = data.correo_masked || valor;
            document.getElementById('otp-destino').textContent = correoDestino;
            mostrarPasoOTP();
        } else {
            mostrarAlerta('alert-box', 'alert-text', 'error', data.message || 'No se encontró una cuenta con esos datos.');
        }
    })
    .catch(() => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Enviar Código';
        mostrarAlerta('alert-box', 'alert-text', 'error', 'Error de conexión. Inténtalo de nuevo.');
    });
}

// ── Mostrar paso OTP ──────────────────────────────────────────────────────────
function mostrarPasoOTP() {
    document.getElementById('step-form').style.display = 'none';
    document.getElementById('step-otp').classList.add('active');
    ocultarAlerta('otp-alert-box');
    iniciarTimer();
    limpiarOTP();
    setTimeout(() => document.getElementById('otp1').focus(), 100);
}

function volverAlPaso1() {
    document.getElementById('step-form').style.display = '';
    document.getElementById('step-otp').classList.remove('active');
    clearInterval(timerInterval);
    limpiarOTP();
}

// ── Cajas OTP ─────────────────────────────────────────────────────────────────
const otpIds = ['otp1','otp2','otp3','otp4','otp5','otp6'];

document.addEventListener('DOMContentLoaded', () => {
    otpIds.forEach((id, i) => {
        const input = document.getElementById(id);

        input.addEventListener('input', (e) => {
            const val = e.target.value.replace(/\D/g,'');
            e.target.value = val;
            if (val && i < otpIds.length - 1) {
                document.getElementById(otpIds[i+1]).focus();
            }
            e.target.classList.toggle('filled', !!val);
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && i > 0) {
                document.getElementById(otpIds[i-1]).focus();
            }
            if (e.key === 'Enter') { verificarOTP(); }
        });

        // Pegar código completo
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
            otpIds.forEach((oid, j) => {
                const box = document.getElementById(oid);
                box.value = pasted[j] || '';
                box.classList.toggle('filled', !!box.value);
            });
            document.getElementById(otpIds[Math.min(pasted.length, 5)]).focus();
        });
    });
});

function limpiarOTP() {
    otpIds.forEach(id => {
        const b = document.getElementById(id);
        b.value = '';
        b.classList.remove('filled');
    });
}

function getOTPValue() {
    return otpIds.map(id => document.getElementById(id).value).join('');
}

// ── Timer ─────────────────────────────────────────────────────────────────────
function iniciarTimer() {
    timerSeconds = 900;
    clearInterval(timerInterval);
    document.getElementById('btn-resend').style.display = 'none';
    actualizarTimerUI();
    timerInterval = setInterval(() => {
        timerSeconds--;
        actualizarTimerUI();
        if (timerSeconds <= 0) {
            clearInterval(timerInterval);
            document.getElementById('timer-countdown').textContent = '00:00';
            document.getElementById('btn-resend').style.display = 'inline';
        }
    }, 1000);
}

function actualizarTimerUI() {
    const m = String(Math.floor(timerSeconds / 60)).padStart(2,'0');
    const s = String(timerSeconds % 60).padStart(2,'0');
    document.getElementById('timer-countdown').textContent = m + ':' + s;
}

function reenviarCodigo() {
    const metodo = metodoActual;
    const valor  = document.getElementById('input-' + metodo).value.trim();
    document.getElementById('btn-resend').style.display = 'none';
    mostrarAlerta('otp-alert-box','otp-alert-text','info','Reenviando código...');

    fetch('./enviar_correo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'metodo=' + encodeURIComponent(metodo) + '&valor=' + encodeURIComponent(valor)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta('otp-alert-box','otp-alert-text','success','¡Nuevo código enviado!');
            iniciarTimer();
            limpiarOTP();
            document.getElementById('otp1').focus();
        } else {
            mostrarAlerta('otp-alert-box','otp-alert-text','error', data.message || 'Error al reenviar.');
            document.getElementById('btn-resend').style.display = 'inline';
        }
    })
    .catch(() => {
        mostrarAlerta('otp-alert-box','otp-alert-text','error','Error de conexión.');
        document.getElementById('btn-resend').style.display = 'inline';
    });
}

// ── Verificar OTP ─────────────────────────────────────────────────────────────
function verificarOTP() {
    const otp   = getOTPValue();
    const metodo = metodoActual;
    const valor  = document.getElementById('input-' + metodo).value.trim();

    if (otp.length < 6) {
        mostrarAlerta('otp-alert-box','otp-alert-text','error','Por favor ingresa los 6 dígitos del código.');
        return;
    }

    const btn = document.getElementById('btn-verificar');
    btn.classList.add('loading');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verificando...';
    ocultarAlerta('otp-alert-box');

    fetch('./verificar_otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'otp=' + encodeURIComponent(otp) + '&metodo=' + encodeURIComponent(metodo) + '&valor=' + encodeURIComponent(valor)
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Verificar Código';

        if (data.success) {
            mostrarAlerta('otp-alert-box','otp-alert-text','success','¡Código correcto! Redirigiendo...');
            clearInterval(timerInterval);
            setTimeout(() => {
                window.location.href = './recuperar_contraseña.php?token=' + encodeURIComponent(data.token);
            }, 1000);
        } else {
            mostrarAlerta('otp-alert-box','otp-alert-text','error', data.message || 'Código incorrecto o expirado.');
            limpiarOTP();
            document.getElementById('otp1').focus();
        }
    })
    .catch(() => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Verificar Código';
        mostrarAlerta('otp-alert-box','otp-alert-text','error','Error de conexión.');
    });
}
</script>
</body>
</html>