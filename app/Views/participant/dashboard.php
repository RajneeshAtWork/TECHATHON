<?php

$user = $user ?? null;

$registrations =
    $registrations ?? [];

$teams =
    $teams ?? [];

$pendingInvitations =
    $pendingInvitations ?? [];

$registrationCount =
    $registrationCount ?? count($registrations);

$teamCount =
    $teamCount ?? count($teams);

$invitationCount =
    $invitationCount ?? count($pendingInvitations);

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
            $title ?? 'Participant Dashboard'
        ) ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">

<?php require __DIR__ . '/partials/navbar.php'; ?>


<div class="container py-4">


    <!-- ============================================================
         WELCOME
         ============================================================ -->

    <div class="mb-4">

        <h1 class="fw-bold mb-1">

            Welcome,

            <?= htmlspecialchars(
                $user['name'] ?? 'Participant'
            ) ?>

        </h1>

        <p class="text-muted mb-0">

            Manage your hackathons, teams,
            registrations, and invitations.

        </p>

    </div>


    <!-- ============================================================
         STATISTICS
         ============================================================ -->

    <div class="row g-4 mb-4">


        <!-- Registrations -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex
                        justify-content-between
                        align-items-center"
                    >

                        <div>

                            <div
                                class="text-muted small mb-1"
                            >
                                My Registrations
                            </div>

                            <h2 class="fw-bold mb-0">

                                <?= (int) $registrationCount ?>

                            </h2>

                        </div>

                        <div
                            class="fs-1 text-primary"
                        >
                            &#128203;
                        </div>

                    </div>

                    <a
                        href="/TECHATHON/public/participant/registrations"
                        class="btn btn-sm btn-outline-primary mt-3"
                    >
                        View Registrations
                    </a>

                </div>

            </div>

        </div>


        <!-- Teams -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex
                        justify-content-between
                        align-items-center"
                    >

                        <div>

                            <div
                                class="text-muted small mb-1"
                            >
                                My Teams
                            </div>

                            <h2 class="fw-bold mb-0">

                                <?= (int) $teamCount ?>

                            </h2>

                        </div>

                        <div
                            class="fs-1 text-success"
                        >
                            &#128101;
                        </div>

                    </div>

                    <a
                        href="/TECHATHON/public/participant/teams"
                        class="btn btn-sm btn-outline-success mt-3"
                    >
                        View Teams
                    </a>

                </div>

            </div>

        </div>


        <!-- Invitations -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex
                        justify-content-between
                        align-items-center"
                    >

                        <div>

                            <div
                                class="text-muted small mb-1"
                            >
                                Pending Invitations
                            </div>

                            <h2 class="fw-bold mb-0">

                                <?= (int) $invitationCount ?>

                            </h2>

                        </div>

                        <div
                            class="fs-1 text-warning"
                        >
                            &#9993;
                        </div>

                    </div>

                    <a
                        href="/TECHATHON/public/participant/invitations"
                        class="btn btn-sm btn-outline-warning mt-3"
                    >
                        View Invitations
                    </a>

                </div>

            </div>

        </div>


    </div>


    <!-- ============================================================
         PENDING INVITATIONS
         ============================================================ -->

    <?php if (
        !empty($pendingInvitations)
    ): ?>

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <div
                    class="d-flex
                    justify-content-between
                    align-items-center
                    mb-3"
                >

                    <h4 class="fw-bold mb-0">
                        Pending Team Invitations
                    </h4>

                    <a
                        href="/TECHATHON/public/participant/invitations"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View All
                    </a>

                </div>


                <?php foreach (
                    array_slice(
                        $pendingInvitations,
                        0,
                        3
                    )
                    as $invitation
                ): ?>

                    <div
                        class="border rounded p-3 mb-3"
                    >

                        <div
                            class="d-flex
                            justify-content-between
                            align-items-start
                            gap-3"
                        >

                            <div>

                                <h5 class="mb-1">

                                    <?= htmlspecialchars(
                                        $invitation[
                                            'team_name'
                                        ]
                                        ?? 'Team'
                                    ) ?>

                                </h5>

                                <div
                                    class="text-muted small"
                                >

                                    Invited by

                                    <strong>

                                        <?= htmlspecialchars(
                                            $invitation[
                                                'invited_by_name'
                                            ]
                                            ?? 'Team Leader'
                                        ) ?>

                                    </strong>

                                </div>

                            </div>


                            <span
                                class="badge bg-warning text-dark"
                            >
                                Pending
                            </span>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endif; ?>


    <!-- ============================================================
         REGISTERED HACKATHONS
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div
                class="d-flex
                justify-content-between
                align-items-center
                mb-3"
            >

                <h4 class="fw-bold mb-0">
                    Registered Hackathons
                </h4>

                <a
                    href="/TECHATHON/public/participant/registrations"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All
                </a>

            </div>


            <?php if (
                empty($registrations)
            ): ?>

                <div class="text-center py-4">

                    <p class="text-muted mb-3">
                        You are not registered
                        for any hackathons yet.
                    </p>

                    <a
                        href="/TECHATHON/public/participant/hackathons"
                        class="btn btn-primary"
                    >
                        Browse Hackathons
                    </a>

                </div>


            <?php else: ?>

                <?php foreach (
                    array_slice(
                        $registrations,
                        0,
                        3
                    )
                    as $registration
                ): ?>

                    <div
                        class="border rounded p-3 mb-3"
                    >

                        <div
                            class="d-flex
                            justify-content-between
                            align-items-start
                            gap-3"
                        >

                            <div>

                                <h5 class="mb-1">

                                    <?= htmlspecialchars(
                                        $registration[
                                            'hackathon_title'
                                        ]
                                        ?? ''
                                    ) ?>

                                </h5>


                                <div
                                    class="text-muted small"
                                >

                                    Participation:

                                    <strong
                                        class="text-capitalize"
                                    >

                                        <?= htmlspecialchars(
                                            $registration[
                                                'registration_type'
                                            ]
                                            ?? ''
                                        ) ?>

                                    </strong>

                                </div>


                                <?php if (
                                    (
                                        $registration[
                                            'registration_type'
                                        ] ?? ''
                                    ) === 'team'
                                    &&
                                    !empty(
                                        $registration[
                                            'team_name'
                                        ]
                                    )
                                ): ?>

                                    <div
                                        class="text-muted small"
                                    >

                                        Team:

                                        <strong>

                                            <?= htmlspecialchars(
                                                $registration[
                                                    'team_name'
                                                ]
                                            ) ?>

                                        </strong>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <span
                                class="badge bg-success"
                            >
                                Registered
                            </span>

                        </div>


                        <div class="mt-3">

                            <a
                                href="/TECHATHON/public/participant/hackathons/<?= (int) $registration['hackathon_id'] ?>"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View Hackathon
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>


    <!-- ============================================================
         MY TEAMS
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div
                class="d-flex
                justify-content-between
                align-items-center
                mb-3"
            >

                <h4 class="fw-bold mb-0">
                    My Teams
                </h4>

                <a
                    href="/TECHATHON/public/participant/teams"
                    class="btn btn-sm btn-outline-success"
                >
                    View All
                </a>

            </div>


            <?php if (
                empty($teams)
            ): ?>

                <div class="text-center py-4">

                    <p class="text-muted mb-3">
                        You are not a member of any team yet.
                    </p>

                    <a
                        href="/TECHATHON/public/participant/teams/create"
                        class="btn btn-success"
                    >
                        Create Team
                    </a>

                </div>


            <?php else: ?>

                <div class="row g-3">

                    <?php foreach (
                        array_slice(
                            $teams,
                            0,
                            3
                        )
                        as $team
                    ): ?>

                        <div class="col-md-4">

                            <div
                                class="border
                                rounded
                                p-3
                                h-100"
                            >

                                <h5 class="mb-2">

                                    <?= htmlspecialchars(
                                        $team['name'] ?? ''
                                    ) ?>

                                </h5>


                                <div
                                    class="text-muted small mb-3"
                                >

                                    Members:

                                    <?= (int) (
                                        $team[
                                            'member_count'
                                        ]
                                        ?? 0
                                    ) ?>

                                </div>


                                <a
                                    href="/TECHATHON/public/participant/teams/<?= (int) $team['id'] ?>"
                                    class="btn btn-sm btn-outline-success"
                                >
                                    View Team
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <!-- ============================================================
         QUICK ACTIONS
         ============================================================ -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h4 class="fw-bold mb-3">
                Quick Actions
            </h4>


            <div
                class="d-flex
                flex-wrap
                gap-2"
            >

                <a
                    href="/TECHATHON/public/participant/hackathons"
                    class="btn btn-primary"
                >
                    Browse Hackathons
                </a>


                <a
                    href="/TECHATHON/public/participant/teams/create"
                    class="btn btn-success"
                >
                    Create Team
                </a>


                <a
                    href="/TECHATHON/public/participant/invitations"
                    class="btn btn-outline-warning"
                >
                    View Invitations
                </a>


                <a
                    href="/TECHATHON/public/participant/registrations"
                    class="btn btn-outline-primary"
                >
                    My Registrations
                </a>

            </div>

        </div>

    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>