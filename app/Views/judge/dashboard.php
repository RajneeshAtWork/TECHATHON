<?php

$judge =
    $judge ?? [];

$assignedHackathons =
    $assignedHackathons ?? [];

$hackathonCount =
    $hackathonCount
    ?? count($assignedHackathons);

$pendingEvaluations =
    $pendingEvaluations ?? 0;

$completedEvaluations =
    $completedEvaluations ?? 0;

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
            $title ?? 'Judge Dashboard'
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


        <!-- BRAND -->

        <a
            href="/TECHATHON/public/judge"
            class="navbar-brand fw-bold"
        >
            TECHATHON
        </a>


        <!-- MOBILE TOGGLER -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#judgeNavbar"
            aria-controls="judgeNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- NAVIGATION -->

        <div
            class="collapse navbar-collapse"
            id="judgeNavbar"
        >


            <ul class="navbar-nav me-auto mb-2 mb-lg-0">


                <!-- DASHBOARD -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="/TECHATHON/public/judge"
                    >
                        Dashboard
                    </a>

                </li>


            </ul>


            <!-- USER AREA -->

            <div
                class="d-flex
                flex-column
                flex-lg-row
                align-items-lg-center
                gap-2"
            >


                <!-- JUDGE NAME -->

                <span class="text-white-50 small">

                    <?= htmlspecialchars(
                        $judge['name'] ?? 'Judge'
                    ) ?>

                </span>


                <!-- LOGOUT -->

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
     MAIN CONTENT
     ================================================================ -->

<div class="container py-4">


    <!-- ============================================================
         WELCOME
         ============================================================ -->

    <div class="mb-4">


        <h1 class="fw-bold mb-1">

            Welcome,

            <?= htmlspecialchars(
                $judge['name'] ?? 'Judge'
            ) ?>

        </h1>


        <p class="text-muted mb-0">

            Review assigned hackathons and evaluate
            participant submissions.

        </p>


    </div>


    <!-- ============================================================
         STATISTICS
         ============================================================ -->

    <div class="row g-4 mb-4">


        <!-- ASSIGNED HACKATHONS -->

        <div class="col-md-4">

            <div
                class="card
                border-0
                shadow-sm
                h-100"
            >

                <div class="card-body">


                    <div
                        class="d-flex
                        justify-content-between
                        align-items-center"
                    >


                        <div>


                            <div
                                class="text-muted
                                small
                                mb-1"
                            >
                                Assigned Hackathons
                            </div>


                            <h2
                                class="fw-bold
                                mb-3"
                            >

                                <?= (int) $hackathonCount ?>

                            </h2>


                            <span class="badge bg-primary">
                                Assigned
                            </span>


                        </div>


                        <div
                            class="fs-1
                            text-primary"
                        >
                            &#128197;
                        </div>


                    </div>


                </div>

            </div>

        </div>


        <!-- PENDING EVALUATIONS -->

        <div class="col-md-4">

            <div
                class="card
                border-0
                shadow-sm
                h-100"
            >

                <div class="card-body">


                    <div
                        class="d-flex
                        justify-content-between
                        align-items-center"
                    >


                        <div>


                            <div
                                class="text-muted
                                small
                                mb-1"
                            >
                                Pending Evaluations
                            </div>


                            <h2
                                class="fw-bold
                                mb-3"
                            >

                                <?= (int) $pendingEvaluations ?>

                            </h2>


                            <?php if (
                                $pendingEvaluations > 0
                            ): ?>

                                <span
                                    class="badge
                                    bg-warning
                                    text-dark"
                                >
                                    Needs Attention
                                </span>

                            <?php else: ?>

                                <span
                                    class="badge
                                    bg-success"
                                >
                                    All Caught Up
                                </span>

                            <?php endif; ?>


                        </div>


                        <div
                            class="fs-1
                            text-warning"
                        >
                            &#128221;
                        </div>


                    </div>


                </div>

            </div>

        </div>


        <!-- COMPLETED EVALUATIONS -->

        <div class="col-md-4">

            <div
                class="card
                border-0
                shadow-sm
                h-100"
            >

                <div class="card-body">


                    <div
                        class="d-flex
                        justify-content-between
                        align-items-center"
                    >


                        <div>


                            <div
                                class="text-muted
                                small
                                mb-1"
                            >
                                Completed Evaluations
                            </div>


                            <h2
                                class="fw-bold
                                mb-3"
                            >

                                <?= (int) $completedEvaluations ?>

                            </h2>


                            <span
                                class="badge
                                bg-success"
                            >
                                Completed
                            </span>


                        </div>


                        <div
                            class="fs-1
                            text-success"
                        >
                            &#10003;
                        </div>


                    </div>


                </div>

            </div>

        </div>


    </div>


    <!-- ============================================================
         ASSIGNED HACKATHONS
         ============================================================ -->

    <div
        class="card
        border-0
        shadow-sm"
    >

        <div class="card-body p-4">


            <!-- SECTION HEADER -->

            <div
                class="d-flex
                justify-content-between
                align-items-center
                flex-wrap
                gap-2
                mb-4"
            >


                <div>


                    <h3
                        class="fw-bold
                        mb-1"
                    >
                        Assigned Hackathons
                    </h3>


                    <p
                        class="text-muted
                        mb-0"
                    >
                        Hackathons assigned to you for evaluation.
                    </p>


                </div>


            </div>


            <?php if (
                empty($assignedHackathons)
            ): ?>


                <!-- NO ASSIGNMENTS -->

                <div
                    class="text-center
                    text-muted
                    py-5"
                >


                    <div
                        class="fs-1
                        mb-3"
                    >
                        &#128197;
                    </div>


                    <h5>
                        No hackathons assigned
                    </h5>


                    <p class="mb-0">

                        You currently have no judging assignments.

                    </p>


                </div>


            <?php else: ?>


                <!-- TABLE -->

                <div class="table-responsive">


                    <table
                        class="table
                        table-hover
                        align-middle
                        mb-0"
                    >


                        <thead>


                            <tr>


                                <th>
                                    Hackathon
                                </th>


                                <th>
                                    Status
                                </th>


                                <th>
                                    Submissions
                                </th>


                                <th>
                                    Evaluated
                                </th>


                                <th>
                                    Assigned
                                </th>


                                <th>
                                    Actions
                                </th>


                            </tr>


                        </thead>


                        <tbody>


                            <?php foreach (
                                $assignedHackathons
                                as $hackathon
                            ): ?>


                                <tr>


                                    <!-- HACKATHON -->

                                    <td>


                                        <strong>

                                            <?= htmlspecialchars(
                                                $hackathon[
                                                    'title'
                                                ] ?? ''
                                            ) ?>

                                        </strong>


                                    </td>


                                    <!-- STATUS -->

                                    <td>


                                        <?php

                                        $hackathonStatus =
                                            strtolower(
                                                (string) (
                                                    $hackathon[
                                                        'status'
                                                    ]
                                                    ?? ''
                                                )
                                            );

                                        ?>


                                        <span
                                            class="badge
                                            <?=
                                                match (
                                                    $hackathonStatus
                                                ) {
                                                    'approved' =>
                                                        'bg-success',

                                                    'registration_open' =>
                                                        'bg-primary',

                                                    'ongoing' =>
                                                        'bg-warning text-dark',

                                                    'completed' =>
                                                        'bg-secondary',

                                                    default =>
                                                        'bg-secondary',
                                                }
                                            ?>"
                                        >

                                            <?= htmlspecialchars(
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $hackathonStatus
                                                    )
                                                )
                                            ) ?>

                                        </span>


                                    </td>


                                    <!-- SUBMISSIONS -->

                                    <td>


                                        <strong>

                                            <?= (int) (
                                                $hackathon[
                                                    'submission_count'
                                                ]
                                                ?? 0
                                            ) ?>

                                        </strong>


                                    </td>


                                    <!-- EVALUATED -->

                                    <td>


                                        <strong>

                                            <?= (int) (
                                                $hackathon[
                                                    'evaluated_submission_count'
                                                ]
                                                ?? 0
                                            ) ?>

                                        </strong>


                                    </td>


                                    <!-- ASSIGNED DATE -->

                                    <td>


                                        <?php

                                        if (
                                            !empty(
                                                $hackathon[
                                                    'assigned_at'
                                                ]
                                            )
                                        ) {

                                            try {

                                                $assignedAt =
                                                    new \DateTime(
                                                        $hackathon[
                                                            'assigned_at'
                                                        ]
                                                    );

                                                echo htmlspecialchars(
                                                    $assignedAt->format(
                                                        'd M Y'
                                                    )
                                                );

                                            } catch (
                                                \Exception $exception
                                            ) {

                                                echo '—';

                                            }

                                        } else {

                                            echo '—';

                                        }

                                        ?>


                                    </td>


                                    <!-- ACTIONS -->

                                    <td>


                                        <a
                                            href="/TECHATHON/public/judge/hackathons/<?= (int) $hackathon['id'] ?>/submissions"
                                            class="btn btn-sm btn-primary"
                                        >
                                            View Submissions
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