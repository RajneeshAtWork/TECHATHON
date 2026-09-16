<?php

use App\Core\Session;

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
        Create Team - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">

<?php require dirname(__DIR__) . '/partials/navbar.php'; ?>
<div class="container py-4">


    <div class="mb-3">

        <a
            href="/TECHATHON/public/participant/teams"
            class="btn btn-outline-secondary btn-sm"
        >
            &larr; Back to My Teams
        </a>

    </div>


    <?php if ($error = Session::getFlash('error')): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <div class="row justify-content-center">

        <div class="col-lg-7">


            <div class="card border-0 shadow-sm">


                <div class="card-body p-4">


                    <h1 class="mb-1">
                        Create Team
                    </h1>

                    <p class="text-muted mb-4">
                        Create a team for hackathon participation.
                    </p>


                    <form
                        method="POST"
                        action="/TECHATHON/public/participant/teams/create"
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
                                for="name"
                                class="form-label"
                            >
                                Team Name
                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                required
                            >

                        </div>


                        <div class="mb-4">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Team Description
                            </label>


                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="5"
                                maxlength="1000"
                            ></textarea>


                            <div class="form-text">

                                Optional. Maximum 1000 characters.

                            </div>

                        </div>


                        <div class="d-flex gap-2">

                            <a
                                href="/TECHATHON/public/participant/teams"
                                class="btn btn-outline-secondary"
                            >
                                Cancel
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Create Team
                            </button>

                        </div>


                    </form>


                </div>

            </div>


        </div>

    </div>


</div>


</body>

</html>
