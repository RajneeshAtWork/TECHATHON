<?php

use App\Core\Csrf;
use App\Core\Session;

$hackathons = $hackathons ?? [];

$csrfToken = $csrfToken ?? Csrf::token();

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
            $title ?? 'Pending Hackathons'
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

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            href="/TECHATHON/public/admin"
            class="navbar-brand"
        >
            TECHATHON Admin
        </a>


        <div class="d-flex gap-2">

            <a
                href="/TECHATHON/public/admin"
                class="btn btn-outline-light btn-sm"
            >
                Dashboard
            </a>

            <a
                href="/TECHATHON/public/admin/hackathons"
                class="btn btn-outline-light btn-sm"
            >
                All Hackathons
            </a>

            <form
                method="POST"
                action="/TECHATHON/public/logout"
                class="d-inline"
            >

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= htmlspecialchars($csrfToken) ?>"
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

</nav>


<!-- ================================================================
     MAIN CONTENT
     ================================================================ -->

<main class="container py-5">


    <!-- ============================================================
         PAGE HEADER
         ============================================================ -->

    <div class="mb-4">

        <h1 class="mb-1">
            Pending Hackathon Approvals
        </h1>

        <p class="text-muted mb-0">
            Review hackathons submitted by organizers.
        </p>

    </div>


    <!-- ============================================================
         FLASH MESSAGES
         ============================================================ -->

    <?php if ($success = Session::getFlash('success')): ?>

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <?= htmlspecialchars($success) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <?php if ($error = Session::getFlash('error')): ?>

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <?= htmlspecialchars($error) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- ============================================================
         EMPTY STATE
         ============================================================ -->

    <?php if (empty($hackathons)): ?>

        <div class="alert alert-success">

            There are no hackathons waiting for approval.

        </div>


    <?php else: ?>


        <!-- ========================================================
             TABLE
             ======================================================== -->

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Hackathon
                                </th>

                                <th>
                                    Organizer
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Participation
                                </th>

                                <th>
                                    Capacity
                                </th>

                                <th>
                                    Registration
                                </th>

                                <th>
                                    Hackathon Dates
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php foreach ($hackathons as $hackathon): ?>

                            <?php

                            $participation =
                                $hackathon[
                                    'participation_type'
                                ] ?? 'individual';

                            ?>

                            <tr>


                                <!-- ==================================================
                                     ID
                                     ================================================== -->

                                <td>

                                    <?= (int) $hackathon['id'] ?>

                                </td>


                                <!-- ==================================================
                                     HACKATHON
                                     ================================================== -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $hackathon['title']
                                        ) ?>

                                    </strong>

                                    <div class="small text-muted">

                                        <?= htmlspecialchars(
                                            $hackathon['slug']
                                        ) ?>

                                    </div>

                                </td>


                                <!-- ==================================================
                                     ORGANIZER
                                     ================================================== -->

                                <td>

                                    <?= htmlspecialchars(
                                        $hackathon['organizer_name']
                                    ) ?>

                                </td>


                                <!-- ==================================================
                                     CATEGORY
                                     ================================================== -->

                                <td>

                                    <?= htmlspecialchars(
                                        $hackathon['category_name']
                                            ?? 'Uncategorized'
                                    ) ?>

                                </td>


                                <!-- ==================================================
                                     PARTICIPATION
                                     ================================================== -->

                                <td>

                                    <?php if (
                                        $participation === 'individual'
                                    ): ?>

                                        <span
                                            class="badge text-bg-primary"
                                        >
                                            Individual
                                        </span>

                                    <?php elseif (
                                        $participation === 'team'
                                    ): ?>

                                        <span
                                            class="badge text-bg-success"
                                        >
                                            Team
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="badge text-bg-info"
                                        >
                                            Individual + Team
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- ==================================================
                                     CAPACITY
                                     ================================================== -->

                                <td>

                                    <?php if (
                                        $participation === 'individual'
                                    ): ?>

                                        <?php if (
                                            $hackathon[
                                                'max_participants'
                                            ] !== null
                                        ): ?>

                                            <?= (int) $hackathon[
                                                'max_participants'
                                            ] ?>

                                            participants

                                        <?php else: ?>

                                            Unlimited participants

                                        <?php endif; ?>


                                    <?php else: ?>

                                        <?php if (
                                            $hackathon['max_teams']
                                            !== null
                                        ): ?>

                                            <?= (int) $hackathon[
                                                'max_teams'
                                            ] ?>

                                            teams

                                        <?php else: ?>

                                            Unlimited teams

                                        <?php endif; ?>

                                    <?php endif; ?>

                                </td>


                                <!-- ==================================================
                                     REGISTRATION
                                     ================================================== -->

                                <td>

                                    <div class="small">

                                        <strong>
                                            Start:
                                        </strong>

                                        <br>

                                        <?= htmlspecialchars(
                                            $hackathon[
                                                'registration_start'
                                            ]
                                        ) ?>

                                    </div>


                                    <div class="small mt-2">

                                        <strong>
                                            End:
                                        </strong>

                                        <br>

                                        <?= htmlspecialchars(
                                            $hackathon[
                                                'registration_end'
                                            ]
                                        ) ?>

                                    </div>

                                </td>


                                <!-- ==================================================
                                     HACKATHON DATES
                                     ================================================== -->

                                <td>

                                    <div class="small">

                                        <strong>
                                            Start:
                                        </strong>

                                        <br>

                                        <?= htmlspecialchars(
                                            $hackathon[
                                                'hackathon_start'
                                            ]
                                        ) ?>

                                    </div>


                                    <div class="small mt-2">

                                        <strong>
                                            End:
                                        </strong>

                                        <br>

                                        <?= htmlspecialchars(
                                            $hackathon[
                                                'hackathon_end'
                                            ]
                                        ) ?>

                                    </div>

                                </td>


                                <!-- ==================================================
                                     ACTIONS
                                     ================================================== -->

                                <td>

                                    <div class="d-flex gap-2">

                                        <!-- ========================================
                                             APPROVE
                                             ======================================== -->

                                        <form
                                            method="POST"
                                            action="/TECHATHON/public/admin/hackathons/<?= (int) $hackathon['id'] ?>/approve"
                                            class="d-inline"
                                            onsubmit="return confirm('Approve this hackathon?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="_csrf_token"
                                                value="<?= htmlspecialchars(
                                                    $csrfToken
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-success btn-sm"
                                            >
                                                Approve
                                            </button>

                                        </form>


                                        <!-- ========================================
                                             REJECT
                                             ======================================== -->

                                        <form
                                            method="POST"
                                            action="/TECHATHON/public/admin/hackathons/<?= (int) $hackathon['id'] ?>/reject"
                                            class="d-inline"
                                            onsubmit="return confirm('Reject this hackathon and return it to draft?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="_csrf_token"
                                                value="<?= htmlspecialchars(
                                                    $csrfToken
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >
                                                Reject
                                            </button>

                                        </form>

                                    </div>


                                    <div class="small text-muted mt-2">

                                        Approve to make this hackathon
                                        available to participants.

                                    </div>

                                </td>


                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    <?php endif; ?>

</main>


<!-- ================================================================
     BOOTSTRAP JAVASCRIPT
     ================================================================ -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>