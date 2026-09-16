<?php

$registrations =
    $registrations ?? [];

$hackathonId =
    (int) ($hackathonId ?? 0);

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
        Hackathon Registrations - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<nav class="navbar navbar-dark bg-dark shadow-sm">

    <div class="container">

        <a
            href="/TECHATHON/public/organizer"
            class="navbar-brand fw-bold"
        >
            TECHATHON
        </a>

        <div class="d-flex gap-2">

            <a
                href="/TECHATHON/public/organizer"
                class="btn btn-outline-light btn-sm"
            >
                Dashboard
            </a>

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

</nav>


<div class="container py-4">


    <div class="mb-3">

        <a
            href="/TECHATHON/public/organizer/hackathons/<?= $hackathonId ?>/manage"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Manage Hackathon
        </a>

    </div>


    <div class="mb-4">

        <h1 class="fw-bold mb-1">
            Registrations
        </h1>

        <p class="text-muted mb-0">
            Participants and teams registered for this hackathon.
        </p>

    </div>


    <?php if (empty($registrations)): ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body p-5 text-center">

                <h4>
                    No registrations yet
                </h4>

                <p class="text-muted mb-0">
                    No participants or teams have registered.
                </p>

            </div>

        </div>


    <?php else: ?>

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">


                <div class="table-responsive">

                    <table
                        class="table
                        table-hover
                        align-middle
                        mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Participant / Leader
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Team
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Registered
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach (
                                $registrations
                                as $registration
                            ): ?>

                                <tr>


                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $registration[
                                                    'participant_name'
                                                ]
                                                ??
                                                $registration[
                                                    'team_leader_name'
                                                ]
                                                ??
                                                '—'
                                            ) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $registration[
                                                'participant_email'
                                            ]
                                            ?? '—'
                                        ) ?>

                                    </td>


                                    <td>

                                        <?php if (
                                            ($registration[
                                                'registration_type'
                                            ] ?? '')
                                            === 'team'
                                        ): ?>

                                            <span
                                                class="badge bg-primary"
                                            >
                                                Team
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="badge bg-secondary"
                                            >
                                                Individual
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if (
                                            !empty(
                                                $registration[
                                                    'team_name'
                                                ]
                                            )
                                        ): ?>

                                            <?= htmlspecialchars(
                                                $registration[
                                                    'team_name'
                                                ]
                                            ) ?>

                                        <?php else: ?>

                                            —

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <span
                                            class="badge bg-success"
                                        >
                                            Registered
                                        </span>

                                    </td>


                                    <td>

                                        <?php

                                        if (
                                            !empty(
                                                $registration[
                                                    'registered_at'
                                                ]
                                            )
                                        ) {
                                            try {

                                                $registeredAt =
                                                    new \DateTime(
                                                        $registration[
                                                            'registered_at'
                                                        ]
                                                    );

                                                echo htmlspecialchars(
                                                    $registeredAt->format(
                                                        'd M Y, h:i A'
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


                                    <td>

                                        <?php if (
                                            ($registration[
                                                'registration_type'
                                            ] ?? '')
                                            === 'team'
                                        ): ?>

                                            <a
                                                href="/TECHATHON/public/organizer/registrations/<?= (int) $registration['registration_id'] ?>/team"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View Team
                                            </a>

                                        <?php else: ?>

                                            <span
                                                class="text-muted small"
                                            >
                                                Individual
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


            </div>

        </div>

    <?php endif; ?>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>