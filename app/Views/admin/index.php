<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?> - TECHATHON</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">
            TECHATHON Admin
        </span>

        <div class="d-flex align-items-center gap-3">
            <span class="text-white">
                <?= htmlspecialchars($user['name']) ?>
            </span>

            <form method="POST" action="/TECHATHON/public/logout">
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="container py-5">

    <div class="mb-4">
        <h1>Admin Dashboard</h1>

        <p class="text-muted mb-0">
            Manage the TECHATHON platform from here.
        </p>
    </div>

    <div class="row g-4">

        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Total Users
                    </h6>

                    <h2>
                        <?= $statistics['total_users'] ?>
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Participants
                    </h6>

                    <h2>
                        <?= $statistics['participants'] ?>
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Organizers
                    </h6>

                    <h2>
                        <?= $statistics['organizers'] ?>
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Judges
                    </h6>

                    <h2>
                        <?= $statistics['judges'] ?>
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Total Hackathons
                    </h6>

                    <h2>
                        <?= $statistics['total_hackathons'] ?>
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Pending Approvals
                    </h6>

                    <h2>
                        <?= $statistics['pending_hackathons'] ?>
                    </h2>
                </div>
            </div>
        </div>

    </div>

</main>

</body>
</html>