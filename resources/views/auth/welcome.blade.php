<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusMap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        .welcome-card {
            max-width: 600px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="welcome-card card mx-auto p-5">
            <div class="text-center mb-4">
                <h1 class="text-primary">
                    <i class="fas fa-map-marked-alt"></i> FocusMap
                </h1>
                <p class="lead text-muted">
                    Organisez vos objectifs visuellement
                </p>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="register.blade.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>S'inscrire
                </a>
                <a href="login.blade.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Connexion
                </a>
            </div>
        </div>
    </div>
</body>
</html>