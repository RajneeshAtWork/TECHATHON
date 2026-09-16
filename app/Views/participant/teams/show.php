<?php

use App\Core\Session;

$team = $team ?? [];
$members = $members ?? [];
$pendingInvitations = $pendingInvitations ?? [];
$isLeader = $isLeader ?? false;
$memberCount = $memberCount ?? 0;

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
            $team['name'] ?? 'Team'
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


    <!-- BACK -->

    <div class="mb-3">

        <a
            href="/TECHATHON/public/participant/teams"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to My Teams
        </a>

    </div>


    <!-- FLASH -->

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


    <div class="row g-4">


        <!-- ========================================================
             TEAM INFORMATION
             ======================================================== -->

        <div class="col-lg-4">


            <div class="card border-0 shadow-sm">


                <div class="card-body">


                    <h1 class="h3 mb-2">

                        <?= htmlspecialchars(
                            $team['name'] ?? ''
                        ) ?>

                    </h1>


                    <?php if (
                        !empty(
                            $team['description']
                        )
                    ): ?>

                        <p class="text-muted">

                            <?= nl2br(
                                htmlspecialchars(
                                    $team['description']
                                )
                            ) ?>

                        </p>

                    <?php else: ?>

                        <p class="text-muted">
                            No team description.
                        </p>

                    <?php endif; ?>


                    <hr>


                    <div class="small text-muted">

                        Members:

                        <strong>

                            <?= (int) $memberCount ?>

                        </strong>

                    </div>


                    <?php if ($isLeader): ?>

                        <div class="mt-3">

                            <span class="badge text-bg-primary">

                                You are the Team Leader

                            </span>

                        </div>

                    <?php endif; ?>


                </div>

            </div>


            <!-- ====================================================
                 INVITE PARTICIPANT
                 ==================================================== -->

            <?php if ($isLeader): ?>


                <div class="card border-0 shadow-sm mt-4">


                    <div class="card-body">


                        <h5 class="mb-2">
                            Invite Participant
                        </h5>


                        <p class="text-muted small">

                            Send an invitation to an existing
                            TECHATHON participant.

                        </p>


                        <form
                            method="POST"
                            action="/TECHATHON/public/participant/teams/<?= (int) $team['id'] ?>/invitations/create"
                        >


                            <input
                                type="hidden"
                                name="_csrf_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken
                                ) ?>"
                            >


                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Participant Email
                                </label>


                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="participant@example.com"
                                    required
                                >

                            </div>


                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Send Invitation
                            </button>


                        </form>


                    </div>

                </div>


            <?php endif; ?>


            <!-- ====================================================
                 PENDING INVITATIONS
                 ==================================================== -->

            <?php if ($isLeader): ?>


                <div class="card border-0 shadow-sm mt-4">


                    <div class="card-body">


                        <h5 class="mb-3">
                            Pending Invitations
                        </h5>


                        <?php if (
                            empty(
                                $pendingInvitations
                            )
                        ): ?>

                            <p class="text-muted small mb-0">

                                No pending invitations.

                            </p>

                        <?php else: ?>


                            <div class="list-group list-group-flush">


                                <?php foreach (
                                    $pendingInvitations
                                    as $invitation
                                ): ?>


                                    <div
                                        class="list-group-item px-0"
                                    >


                                        <div class="fw-semibold">

                                            <?= htmlspecialchars(
                                                $invitation[
                                                    'invited_name'
                                                ]
                                            ) ?>

                                        </div>


                                        <div class="small text-muted">

                                            <?= htmlspecialchars(
                                                $invitation[
                                                    'invited_email'
                                                ]
                                            ) ?>

                                        </div>


                                        <?php if (
                                            !empty(
                                                $invitation[
                                                    'expires_at'
                                                ]
                                            )
                                        ): ?>

                                            <div class="small text-muted mt-1">

                                                Expires:

                                                <?= htmlspecialchars(
                                                    (new DateTimeImmutable(
                                                        $invitation[
                                                            'expires_at'
                                                        ]
                                                    ))->format(
                                                        'd M Y, h:i A'
                                                    )
                                                ) ?>

                                            </div>

                                        <?php endif; ?>


                                        <span
                                            class="badge text-bg-warning mt-2"
                                        >
                                            Pending
                                        </span>


                                    </div>


                                <?php endforeach; ?>


                            </div>


                        <?php endif; ?>


                    </div>

                </div>


            <?php endif; ?>


        </div>


        <!-- ========================================================
             TEAM MEMBERS
             ======================================================== -->

        <div class="col-lg-8">


            <div class="card border-0 shadow-sm">


                <div class="card-header bg-white">

                    <div
                        class="d-flex justify-content-between
                        align-items-center"
                    >

                        <h4 class="mb-0">
                            Team Members
                        </h4>


                        <span
                            class="badge bg-light text-dark"
                        >

                            <?= (int) $memberCount ?>

                            members

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">


                    <?php if (empty($members)): ?>

                        <div class="text-center py-5">

                            <p class="text-muted mb-0">
                                No team members found.
                            </p>

                        </div>

                    <?php else: ?>


                        <div class="table-responsive">


                            <table
                                class="table table-hover
                                align-middle mb-0"
                            >


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

                                        <?php if ($isLeader): ?>

                                            <th
                                                class="text-end"
                                            >
                                                Action
                                            </th>

                                        <?php endif; ?>

                                    </tr>

                                </thead>


                                <tbody>


                                <?php foreach (
                                    $members
                                    as $member
                                ): ?>


                                    <tr>


                                        <td>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $member[
                                                        'name'
                                                    ]
                                                ) ?>

                                            </strong>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $member[
                                                    'email'
                                                ]
                                            ) ?>

                                        </td>


                                        <td>

                                            <?php if (
                                                $member[
                                                    'role'
                                                ]
                                                === 'leader'
                                            ): ?>

                                                <span
                                                    class="badge text-bg-primary"
                                                >
                                                    Leader
                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="badge text-bg-secondary"
                                                >
                                                    Member
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                (new DateTimeImmutable(
                                                    $member[
                                                        'joined_at'
                                                    ]
                                                ))->format(
                                                    'd M Y'
                                                )
                                            ) ?>

                                        </td>


                                        <?php if ($isLeader): ?>


                                            <td
                                                class="text-end"
                                            >


                                                <?php if (
                                                    $member[
                                                        'role'
                                                    ]
                                                    !== 'leader'
                                                ): ?>


                                                    <form
                                                        method="POST"
                                                        action="/TECHATHON/public/participant/teams/<?= (int) $team['id'] ?>/members/remove"
                                                        class="d-inline"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="_csrf_token"
                                                            value="<?= htmlspecialchars(
                                                                $csrfToken
                                                            ) ?>"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            name="user_id"
                                                            value="<?= (int) $member['user_id'] ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Remove this member from the team?');"
                                                        >
                                                            Remove
                                                        </button>

                                                    </form>


                                                <?php else: ?>

                                                    <span class="text-muted small">
                                                        —
                                                    </span>

                                                <?php endif; ?>


                                            </td>


                                        <?php endif; ?>


                                    </tr>


                                <?php endforeach; ?>


                                </tbody>


                            </table>


                        </div>


                    <?php endif; ?>


                </div>


            </div>


        </div>


    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>