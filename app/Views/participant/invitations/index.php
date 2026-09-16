<?php

use App\Core\Session;

$invitations = $invitations ?? [];

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
        Team Invitations - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">
<?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

<div class="container py-4">


    <div class="mb-4">

        <h1 class="mb-1">
            Team Invitations
        </h1>

        <p class="text-muted mb-0">
            Review invitations from hackathon teams.
        </p>

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


    <?php if (empty($invitations)): ?>


        <div class="card border-0 shadow-sm">


            <div class="card-body text-center py-5">


                <h4 class="mb-2">
                    No pending invitations
                </h4>


                <p class="text-muted mb-3">
                    You do not have any pending team invitations.
                </p>


                <a
                    href="/TECHATHON/public/participant/teams"
                    class="btn btn-outline-primary"
                >
                    My Teams
                </a>


            </div>


        </div>


    <?php else: ?>


        <div class="row g-4">


            <?php foreach (
                $invitations
                as $invitation
            ): ?>


                <div class="col-md-6">


                    <div class="card border-0 shadow-sm h-100">


                        <div class="card-body">


                            <span
                                class="badge text-bg-warning mb-3"
                            >
                                Pending Invitation
                            </span>


                            <h4 class="mb-2">

                                <?= htmlspecialchars(
                                    $invitation[
                                        'team_name'
                                    ]
                                ) ?>

                            </h4>


                            <?php if (
                                !empty(
                                    $invitation[
                                        'team_description'
                                    ]
                                )
                            ): ?>

                                <p class="text-muted">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $invitation[
                                                'team_description'
                                            ]
                                        )
                                    ) ?>

                                </p>

                            <?php endif; ?>


                            <div class="small text-muted mb-3">

                                Invited by:

                                <strong>

                                    <?= htmlspecialchars(
                                        $invitation[
                                            'inviter_name'
                                        ]
                                    ) ?>

                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $invitation[
                                        'inviter_email'
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

                                <div class="small text-muted mb-4">

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


                            <div class="d-flex gap-2">


                                <form
                                    method="POST"
                                    action="/TECHATHON/public/participant/invitations/<?= (int) $invitation['id'] ?>/accept"
                                    class="flex-fill"
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
                                        class="btn btn-success w-100"
                                    >
                                        Accept
                                    </button>

                                </form>


                                <form
                                    method="POST"
                                    action="/TECHATHON/public/participant/invitations/<?= (int) $invitation['id'] ?>/reject"
                                    class="flex-fill"
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
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Reject this team invitation?');"
                                    >
                                        Reject
                                    </button>

                                </form>


                            </div>


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