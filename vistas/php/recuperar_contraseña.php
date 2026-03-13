<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña — MultiShop</title>
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
        }

        .logo {
            width: 70px; height: 70px;
            background-image: url("../../public/img/Multi.png");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            display: block;
            margin: 0 auto 0.75rem;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4));
        }

        .card-header-custom h1 { color:#fff; font-size:1.4rem; font-weight:700; }
        .card-header-custom p  { color:rgba(255,255,255,0.65); font-size:0.82rem; margin-top:0.25rem; }

        .card-body-custom { padding: 1.75rem 2rem 2rem; }

        .input-group-custom { margin-bottom: 1.1rem; }
        .input-group-custom label { display:block; font-size:0.82rem; font-weight:600; color:#333; margin-bottom:0.4rem; }

        .input-wrapper { position: relative; }
        .input-wrapper .icon-left {
            position: absolute; left:14px; top:50%; transform:translateY(-50%);
            color:#aaa; font-size:0.95rem; pointer-events:none;
        }
        .input-wrapper .icon-right {
            position: absolute; right:14px; top:50%; transform:translateY(-50%);
            color:#aaa; font-size:0.95rem; cursor:pointer; transition:color 0.2s;
        }
        .input-wrapper .icon-right:hover { color:#0f3460; }

        .input-wrapper input {
            width:100%;
            padding:0.75rem 2.8rem 0.75rem 2.6rem;
            border:2px solid #e8ecf0;
            border-radius:12px;
            font-family:'Poppins',sans-serif;
            font-size:0.9rem; color:#222;
            background:#fafbfc;
            transition:all 0.2s ease;
            outline:none;
        }

        .input-wrapper input:focus {
            border-color:#0f3460;
            background:#fff;
            box-shadow:0 0 0 4px rgba(15,52,96,0.08);
        }

        /* ── Indicador de fuerza ── */
        .strength-bar-wrapper {
            margin-top: 8px;
            display: none;
        }

        .strength-bars {
            display: flex;
            gap: 5px;
            margin-bottom: 4px;
        }

        .sbar {
            flex: 1;
            height: 5px;
            border-radius: 3px;
            background: #e0e5ee;
            transition: background 0.3s ease;
        }

        .strength-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #999;
        }

        .strength-requirements {
            margin-top: 8px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
        }

        .req {
            font-size: 0.75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s;
        }

        .req.met { color: #059669; }
        .req i { font-size: 0.7rem; }

        /* ── Botón ── */
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
            margin-top: 0.75rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #0d0d1a, #0a2344);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(15,52,96,0.4);
        }

        .btn-submit:active { transform: translateY(0); }

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
        .status-alert.success { background:#d1fae5; color:#065f46; border:1.5px solid #6ee7b7; }
        .status-alert.error   { background:#fee2e2; color:#991b1b; border:1.5px solid #fca5a5; }
    </style>
</head>
<body>
<div class="bg-overlay"></div>

<div class="card-recovery">

    <div class="card-header-custom">
        <span class="logo"></span>
        <h1>Nueva Contraseña</h1>
        <p>Elige una contraseña segura para tu cuenta</p>
    </div>

    <div class="card-body-custom">
        <?php
        include 'config.php';
        date_default_timezone_set('UTC');

        // Mostrar mensaje de éxito si se acaba de cambiar la contraseña
        if (isset($_SESSION['success_message'])):
        ?>
        <div class="status-alert show success" id="alert-box">
            <i class="fa-solid fa-circle-check"></i>
            <span><?php echo htmlspecialchars($_SESSION['success_message']); ?></span>
        </div>
        <?php
            unset($_SESSION['success_message']);
            echo '<script>setTimeout(function(){ window.location.href = "../inicio.php"; }, 3500);</script>';
        endif;

        if (isset($_GET['token'])):
            $token = $_GET['token'];
            $stmt  = $pdo->prepare("SELECT id, nombre, expira_token FROM suscripciones WHERE token = ? LIMIT 1");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && strtotime($user['expira_token']) > time()):
        ?>

        <!-- Estado vacío por defecto (oculto) -->
        <div class="status-alert" id="alert-box">
            <i class="fa-solid fa-circle-info"></i>
            <span id="alert-text"></span>
        </div>

        <form id="form-nueva" onsubmit="cambiarPassword(event)">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <!-- Nueva contraseña -->
            <div class="input-group-custom">
                <label for="password">Nueva contraseña</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock icon-left"></i>
                    <input type="password" id="password" name="password"
                           placeholder="Mínimo 8 caracteres" required
                           oninput="evaluarFuerza(this.value)">
                    <i class="fa-solid fa-eye icon-right" onclick="togglePass('password',this)"></i>
                </div>

                <!-- Indicador de fuerza -->
                <div class="strength-bar-wrapper" id="strength-wrapper">
                    <div class="strength-bars">
                        <div class="sbar" id="sb1"></div>
                        <div class="sbar" id="sb2"></div>
                        <div class="sbar" id="sb3"></div>
                        <div class="sbar" id="sb4"></div>
                    </div>
                    <span class="strength-label" id="strength-label">—</span>
                    <div class="strength-requirements">
                        <div class="req" id="req-len"><i class="fa-solid fa-circle-check"></i> 8 caracteres</div>
                        <div class="req" id="req-upper"><i class="fa-solid fa-circle-check"></i> Mayúscula</div>
                        <div class="req" id="req-num"><i class="fa-solid fa-circle-check"></i> Número</div>
                        <div class="req" id="req-sym"><i class="fa-solid fa-circle-check"></i> Símbolo</div>
                    </div>
                </div>
            </div>

            <!-- Confirmar contraseña -->
            <div class="input-group-custom">
                <label for="confirm_password">Confirmar contraseña</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock icon-left"></i>
                    <input type="password" id="confirm_password" name="confirm_password"
                           placeholder="Repite tu nueva contraseña" required
                           oninput="verificarCoincidencia()">
                    <i class="fa-solid fa-eye icon-right" onclick="togglePass('confirm_password',this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btn-cambiar">
                <i class="fa-solid fa-shield-halved"></i>
                Cambiar Contraseña
            </button>
        </form>

        <?php
            elseif ($user):
                echo '<div class="status-alert show error"><i class="fa-solid fa-circle-exclamation"></i><span>El enlace ha expirado. <a href="./recuperar_solicitud.php">Solicita uno nuevo</a>.</span></div>';
            else:
                echo '<div class="status-alert show error"><i class="fa-solid fa-circle-exclamation"></i><span>Enlace inválido. <a href="./recuperar_solicitud.php">Volver al inicio</a>.</span></div>';
            endif;
        endif;
        ?>
    </div>
</div>

<script>
// ── Mostrar/ocultar contraseña ────────────────────────────────────────────────
function togglePass(id, icon) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye','fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash','fa-eye');
    }
}

// ── Indicador de fuerza ───────────────────────────────────────────────────────
function evaluarFuerza(val) {
    const wrapper = document.getElementById('strength-wrapper');
    wrapper.style.display = val.length ? 'block' : 'none';
    if (!val.length) return;

    const checks = {
        len:   val.length >= 8,
        upper: /[A-Z]/.test(val),
        num:   /\d/.test(val),
        sym:   /[^A-Za-z0-9]/.test(val),
    };

    // Requerimientos visuales
    Object.entries(checks).forEach(([k,v]) => {
        document.getElementById('req-'+k).classList.toggle('met', v);
    });

    const score = Object.values(checks).filter(Boolean).length;

    const colors  = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels  = ['Débil','Regular','Buena','Fuerte'];
    const bars    = [document.getElementById('sb1'),document.getElementById('sb2'),
                     document.getElementById('sb3'),document.getElementById('sb4')];

    bars.forEach((b,i) => {
        b.style.background = i < score ? colors[score-1] : '#e0e5ee';
    });

    document.getElementById('strength-label').style.color  = colors[score-1] || '#999';
    document.getElementById('strength-label').textContent  = labels[score-1] || '—';
}

// ── Verificar coincidencia ────────────────────────────────────────────────────
function verificarCoincidencia() {
    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('confirm_password');
    const ok  = p1 === p2.value && p2.value.length > 0;
    p2.style.borderColor = p2.value.length ? (ok ? '#22c55e' : '#ef4444') : '';
}

// ── Alerta ────────────────────────────────────────────────────────────────────
function mostrarAlerta(tipo, msg) {
    const box  = document.getElementById('alert-box');
    const text = document.getElementById('alert-text');
    const icon = box.querySelector('i');
    box.className = 'status-alert show ' + tipo;
    if (text)  text.textContent = msg;
    const iconMap = { success:'fa-circle-check', error:'fa-circle-exclamation' };
    icon.className = 'fa-solid ' + (iconMap[tipo] || 'fa-circle-info');
}

// ── Enviar cambio de contraseña ───────────────────────────────────────────────
function cambiarPassword(e) {
    e.preventDefault();

    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('confirm_password').value;
    const btn = document.getElementById('btn-cambiar');

    if (p1.length < 8) {
        mostrarAlerta('error','La contraseña debe tener al menos 8 caracteres.');
        return;
    }
    if (p1 !== p2) {
        mostrarAlerta('error','Las contraseñas no coinciden.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';

    const formData = new FormData(document.getElementById('form-nueva'));

    fetch('./cambiar_contraseña.php', { method:'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta('success', '¡Contraseña actualizada! Redirigiendo al inicio...');
            document.getElementById('form-nueva').style.display = 'none';
            setTimeout(() => { window.location.href = '../inicio.php'; }, 2500);
        } else {
            mostrarAlerta('error', data.message || 'No se pudo cambiar la contraseña.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-halved"></i> Cambiar Contraseña';
        }
    })
    .catch(() => {
        mostrarAlerta('error','Error de conexión. Inténtalo de nuevo.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-shield-halved"></i> Cambiar Contraseña';
    });
}
</script>
</body>
</html>