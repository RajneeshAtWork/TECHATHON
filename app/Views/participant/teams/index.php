<?php

use App\Core\Session;

$teams = $teams ?? [];

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
            $title ?? 'My Teams'
        ) ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">
<?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

<div class="container py-4">


    <!-- HEADER -->

    <div
        class="d-flex flex-wrap
        justify-content-between
        align-items-center
        gap-3 mb-4"
    >

        <div>

            <h1 class="mb-1">
                My Teams
            </h1>

            <p class="text-muted mb-0">
                Create and manage your hackathon teams.
            </p>

        </div>


        <a
            href="/TECHATHON/public/participant/teams/create"
            class="btn btn-primary"
        >
            Create Team
        </a>

    </div>


    <!-- FLASH MESSAGES -->

    <?php if ($success = Session::getFlash('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= htmlspecialchars($success) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <?php if ($error = Session::getFlash('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <?= htmlspecialchars($error) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- TEAMS -->

    <?php if (empty($teams)): ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <h4 class="mb-2">
                    You do not have a team yet
                </h4>

                <p class="text-muted mb-3">
                    Create a team to participate in team-based hackathons.
                </p>

                <a
                    href="/TECHATHON/public/participant/teams/create"
                    class="btn btn-primary"
                >
                    Create Your First Team
                </a>

            </div>

        </div>

    <?php else: ?>


        <div class="row g-4">


            <?php foreach ($teams as $team): ?>


                <div class="col-md-6 col-xl-4">


                    <div class="card border-0 shadow-sm h-100">


                        <div class="card-body d-flex flex-column">


                            <h4 class="mb-2">

                                <?= htmlspecialchars(
                                    $team['name']
                                ) ?>

                            </h4>


                            <?php if (
                                !empty(
                                    $team['description']
                                )
                            ): ?>

                                <p class="text-muted">

                                    <?= htmlspecialchars(
                                        mb_strimwidth(
                                            $team['description'],
                                            0,
                                            160,
                                            '...'
                                        )
                                    ) ?>

                                </p>

                            <?php else: ?>

                                <p class="text-muted">

                                    No team description.

                                </p>

                            <?php endif; ?>


                            <div class="small text-muted mb-3">

                                Created:

                                <?= htmlspecialchars(
                                    (new DateTimeImmutable(
                                        $team['created_at']
                                    ))->format(
                                        'd M Y, h:i A'
                                    )
                                ) ?>

                            </div>


                            <a
                                href="/TECHATHON/public/participant/teams/<?= (int) $team['id'] ?>"
                                class="btn btn-outline-primary mt-auto"
                            >
                                Manage Team
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