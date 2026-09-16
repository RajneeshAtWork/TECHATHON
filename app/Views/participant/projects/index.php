<?php

$projects = $projects ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars(
            $title ?? 'My Projects'
        ) ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">

<?php require __DIR__ . '/../partials/navbar.php'; ?>


<div class="container py-4">


    <div class="d-flex
                justify-content-between
                align-items-center
                mb-4">

        <div>

            <h1 class="fw-bold mb-1">
                My Projects
            </h1>

            <p class="text-muted mb-0">
                Projects connected to your hackathon registrations.
            </p>

        </div>


        <a
            href="/TECHATHON/public/participant/registrations"
            class="btn btn-outline-primary"
        >
            My Registrations
        </a>

    </div>


    <?php if (empty($projects)): ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body p-5 text-center">

                <h4 class="mb-2">
                    No projects yet
                </h4>

                <p class="text-muted mb-4">
                    Create a project from one of your
                    hackathon registrations.
                </p>

                <a
                    href="/TECHATHON/public/participant/registrations"
                    class="btn btn-primary"
                >
                    View Registrations
                </a>

            </div>

        </div>


    <?php else: ?>

        <div class="row g-4">

            <?php foreach ($projects as $project): ?>

                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <div
                                class="d-flex
                                justify-content-between
                                align-items-start
                                gap-3 mb-3"
                            >

                                <div>

                                    <h4 class="fw-bold mb-1">

                                        <?= htmlspecialchars(
                                            $project['title'] ?? ''
                                        ) ?>

                                    </h4>

                                    <div class="text-muted small">

                                        <?= htmlspecialchars(
                                            $project[
                                                'hackathon_title'
                                            ] ?? ''
                                        ) ?>

                                    </div>

                                </div>


                                <span
                                    class="badge bg-success"
                                >
                                    <?= (
                                        ($project['latest_status']
                                            ?? '') === 'submitted'
                                    )
                                        ? 'Submitted'
                                        : 'Draft'
                                    ?>
                                </span>

                            </div>


                            <?php if (
                                !empty(
                                    $project['team_name']
                                )
                            ): ?>

                                <div class="mb-3">

                                    <div
                                        class="text-muted small"
                                    >
                                        Team
                                    </div>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $project['team_name']
                                        ) ?>

                                    </strong>

                                </div>

                            <?php endif; ?>


                            <p class="text-muted">

                                <?= nl2br(
                                    htmlspecialchars(
                                        mb_strimwidth(
                                            $project[
                                                'description'
                                            ] ?? '',
                                            0,
                                            220,
                                            '...'
                                        )
                                    )
                                ) ?>

                            </p>


                            <a
                                href="/TECHATHON/public/participant/projects/<?= (int) $project['id'] ?>"
                                class="btn btn-outline-primary"
                            >
                                View Project
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>