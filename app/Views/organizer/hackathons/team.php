<?php

$organizer =
    $organizer ?? [];

$hackathon =
    $hackathon ?? [];

$team =
    $team ?? [];

$members =
    $members ?? [];

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
            $title ?? 'Team'
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
            href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/teams"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to Teams
        </a>

    </div>


    <!-- HEADER -->

    <div
        class="d-flex
        justify-content-between
        align-items-start
        flex-wrap
        gap-3
        mb-4"
    >


        <div>

            <h1 class="fw-bold mb-1">

                <?= htmlspecialchars(
                    $team['name']
                    ?? 'Team'
                ) ?>

            </h1>


            <p class="text-muted mb-1">

                Hackathon:

                <strong>

                    <?= htmlspecialchars(
                        $hackathon['title']
                        ?? ''
                    ) ?>

                </strong>

            </p>


            <?php if (
                !empty(
                    $team['description']
                )
            ): ?>

                <p class="text-muted mb-0">

                    <?= nl2br(
                        htmlspecialchars(
                            $team[
                                'description'
                            ]
                        )
                    ) ?>

                </p>

            <?php endif; ?>

        </div>


        <?php

        $teamStatus =
            strtolower(
                (string) (
                    $team['status']
                    ?? 'active'
                )
            );


        $teamStatusClass =
            match ($teamStatus) {

                'active' =>
                    'bg-success',

                'inactive' =>
                    'bg-secondary',

                'disbanded' =>
                    'bg-danger',

                default =>
                    'bg-secondary',
            };

        ?>


        <span
            class="badge
            <?= $teamStatusClass ?>
            fs-6"
        >

            <?= htmlspecialchars(
                ucwords(
                    $teamStatus
                )
            ) ?>

        </span>


    </div>


    <!-- ============================================================
         TEAM SUMMARY
         ============================================================ -->

    <div class="row g-3 mb-4">


        <!-- LEADER -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Team Leader
                    </div>

                    <div class="fw-bold">

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

                </div>

            </div>

        </div>


        <!-- MEMBER COUNT -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Team Members
                    </div>

                    <div class="fw-bold fs-3">
                        <?= count($members) ?>
                    </div>

                </div>

            </div>

        </div>


        <!-- REGISTERED -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Registered At
                    </div>

                    <div class="fw-bold">

                        <?php if (
                            !empty(
                                $team[
                                    'registered_at'
                                ]
                            )
                        ): ?>

                            <?= htmlspecialchars(
                                date(
                                    'd M Y',
                                    strtotime(
                                        $team[
                                            'registered_at'
                                        ]
                                    )
                                )
                            ) ?>

                            <br>

                            <small class="text-muted">

                                <?= htmlspecialchars(
                                    date(
                                        'h:i A',
                                        strtotime(
                                            $team[
                                                'registered_at'
                                            ]
                                        )
                                    )
                                ) ?>

                            </small>

                        <?php else: ?>

                            —

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>


    </div>


    <!-- ============================================================
         MEMBERS
         ============================================================ -->

    <div class="card border-0 shadow-sm">


        <div class="card-header bg-white py-3">

            <h4 class="fw-bold mb-1">
                Team Members
            </h4>

            <p class="text-muted mb-0">

                Members currently belonging
                to this participating team.

            </p>

        </div>


        <div class="card-body p-0">


            <?php if (empty($members)): ?>

                <div class="text-center py-5">

                    <h5>
                        No members found
                    </h5>

                </div>

            <?php else: ?>


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Joined
                            </th>

                        </tr>

                        </thead>


                        <tbody>


                        <?php foreach (
                            $members
                            as $member
                        ): ?>

                            <tr>


                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $member['name']
                                            ?? ''
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $member['email']
                                        ?? ''
                                    ) ?>

                                </td>


                                <td>

                                    <?php

                                    $memberRole =
                                        strtolower(
                                            (string) (
                                                $member['role']
                                                ?? 'member'
                                            )
                                        );

                                    $memberRoleClass =
                                        $memberRole === 'leader'
                                            ? 'warning text-dark'
                                            : 'light text-dark';

                                    ?>


                                    <span
                                        class="badge bg-<?= $memberRoleClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            ucfirst(
                                                $memberRole
                                            )
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        !empty(
                                            $member[
                                                'joined_at'
                                            ]
                                        )
                                    ): ?>

                                        <?= htmlspecialchars(
                                            date(
                                                'd M Y, h:i A',
                                                strtotime(
                                                    $member[
                                                        'joined_at'
                                                    ]
                                                )
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>

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