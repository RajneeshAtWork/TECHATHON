<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($title) ?> - TECHATHON</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm border-0">

        <div class="card-body text-center py-5">

            <h1 class="fw-bold">
                Organizer Profile Not Found
            </h1>

            <p class="text-muted">
                Your account has the organizer role, but an organizer
                profile has not been created yet.
            </p>

            <a
                href="/TECHATHON/public/"
                class="btn btn-primary"
            >
                Back to Home
            </a>

        </div>

    </div>

</div>

</body>
</html>
