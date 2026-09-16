<?php

$organizer =
    $organizer ?? [];

$hackathon =
    $hackathon ?? [];

$teams =
    $teams ?? [];

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
            $title ?? 'Teams'
        ) ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<!-- ================================================================
     NAVBAR
     ================================================================ -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

    <div class="container">

        <a
            href="/TECHATHON/public/organizer"
            class="navbar-brand fw-bold"
        >
            TECHATHON
        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#organizerNavbar"
            aria-controls="organizerNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="organizerNavbar"
        >

            <ul class="navbar-nav me-auto">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/TECHATHON/public/organizer"
                    >
                        Dashboard
                    </a>

                </li>

            </ul>


            <div
                class="d-flex
                flex-column
                flex-lg-row
                align-items-lg-center
                gap-2"
            >

                <span class="text-white-50 small">

                    <?= htmlspecialchars(
                        $organizer['organization_name']
                        ??
                        $organizer['name']
                        ??
                        'Organizer'
                    ) ?>

                </span>


                <form
                    method="POST"
                    action="/TECHATHON/public/logout"
                    class="m-0"
                >

                    <button
                        type="submit"
                        class="btn btn-outline-light btn-sm"
                    >
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>


<!-- ================================================================
     MAIN
     ================================================================ -->

<div class="container py-4">


    <!-- BACK -->

    <div class="mb-3">

        <a
            href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/manage"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to Hackathon Management
        </a>

    </div>


    <!-- HEADER -->

    <div class="mb-4">

        <h1 class="fw-bold mb-1">
            Teams
        </h1>

        <p class="text-muted mb-1">

            <?= htmlspecialchars(
                $hackathon['title']
                ?? ''
            ) ?>

        </p>

        <p class="text-muted mb-0">
            View teams registered for this hackathon
            and their members.
        </p>

    </div>


    <!-- ============================================================
         SUMMARY
         ============================================================ -->

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Registered Teams
                    </div>

                    <h2 class="fw-bold mb-0">
                        <?= count($teams) ?>
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Maximum Teams
                    </div>

                    <h2 class="fw-bold mb-0">

                        <?php if (
                            isset($hackathon['max_teams'])
                            && $hackathon['max_teams'] !== null
                        ): ?>

                            <?= (int) $hackathon['max_teams'] ?>

                        <?php else: ?>

                            Unlimited

                        <?php endif; ?>

                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Participation
                    </div>

                    <h2 class="fw-bold mb-0 fs-4">

                        <?= htmlspecialchars(
                            ucwords(
                                (string) (
                                    $hackathon[
                                        'participation_type'
                                    ]
                                    ?? 'individual'
                                )
                            )
                        ) ?>

                    </h2>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         TEAMS TABLE
         ============================================================ -->

    <div class="card border-0 shadow-sm">


        <div class="card-header bg-white py-3">

            <h4 class="fw-bold mb-1">
                Participating Teams
            </h4>

            <p class="text-muted mb-0">
                Teams with an active registration for this hackathon.
            </p>

        </div>


        <div class="card-body p-0">


            <?php if (empty($teams)): ?>

                <div class="text-center py-5 px-3">

                    <h5 class="mb-2">
                        No teams registered yet
                    </h5>

                    <p class="text-muted mb-0">
                        Registered teams will appear here.
                    </p>

                </div>

            <?php else: ?>


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                Team
                            </th>

                            <th>
                                Leader
                            </th>

                            <th>
                                Members
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Registered
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>


                        <?php foreach ($teams as $team): ?>

                            <tr>


                                <!-- TEAM -->

                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $team['name']
                                            ?? ''
                                        ) ?>

                                    </div>


                                    <?php if (
                                        !empty(
                                            $team['description']
                                        )
                                    ): ?>

                                        <small class="text-muted">

                                            <?= htmlspecialchars(
                                                mb_strimwidth(
                                                    $team[
                                                        'description'
                                                    ],
                                                    0,
                                                    80,
                                                    '...'
                                                )
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>


                                <!-- LEADER -->

                                <td>

                                    <div>

                                        <?= htmlspecialchars(
                                            $team['leader_name']
                                            ?? '—'
                                        ) ?>

                                    </div>

                                    <?php if (
                                        !empty(
                                            $team['leader_email']
                                        )
                                    ): ?>

                                        <small class="text-muted">

                                            <?= htmlspecialchars(
                                                $team['leader_email']
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>


                                <!-- MEMBERS -->

                                <td>

                                    <span
                                        class="badge bg-light text-dark"
                                    >

                                        <?= (int) (
                                            $team[
                                                'member_count'
                                            ]
                                            ?? 0
                                        ) ?>

                                        members

                                    </span>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <?php
                                    $teamStatus =
                                        strtolower(
                                            (string) (
                                                $team['status']
                                                ?? 'active'
                                            )
                                        );

                                    $teamStatusClass = match (
                                        $teamStatus
                                    ) {
                                        'active' =>
                                            'success',

                                        'inactive' =>
                                            'secondary',

                                        'disbanded' =>
                                            'danger',

                                        default =>
                                            'secondary',
                                    };
                                    ?>


                                    <span
                                        class="badge bg-<?= $teamStatusClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            ucwords(
                                                $teamStatus
                                            )
                                        ) ?>

                                    </span>

                                </td>


                                <!-- REGISTERED -->

                                <td>

                                    <?php if (
                                        !empty(
                                            $team[
                                                'registered_at'
                                            ]
                                        )
                                    ): ?>

                                        <?= htmlspecialchars(
                                            date(
                                                'd M Y, h:i A',
                                                strtotime(
                                                    $team[
                                                        'registered_at'
                                                    ]
                                                )
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>

                                </td>


                                <!-- ACTION -->

                                <td class="text-end">

                                    <a
                                        href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/teams/<?= (int) $team['id'] ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Team
                                    </a>

                                </td>


                            </tr>

                        <?php endforeach; ?>


                        </tbody>

                    </table>

                </div>


            <?php endif; ?>


        </div>

    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>