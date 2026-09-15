<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($title) ?> - TECHATHON</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h1 class="h3">TECHATHON</h1>
                        <p class="text-muted mb-0">
                            Create your account
                        </p>
                    </div>

                    <?php if ($error = \App\Core\Session::getFlash('error')): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endif; ?>

                    <?php if ($success = \App\Core\Session::getFlash('success')): ?>

                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>

                    <?php endif; ?>

                    <form
                        method="POST"
                        action="/TECHATHON/public/register"
                    >

                        <div class="mb-3">
                            <label
                                for="name"
                                class="form-label"
                            >
                                Full Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                required
                                maxlength="100"
                                autocomplete="name"
                            >
                        </div>

                        <div class="mb-3">
                            <label
                                for="email"
                                class="form-label"
                            >
                                Email Address
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                maxlength="150"
                                autocomplete="email"
                            >
                        </div>

                        <div class="mb-3">
                            <label
                                for="password"
                                class="form-label"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <div class="form-text">
                                Password must be at least 8 characters.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label
                                for="password_confirmation"
                                class="form-label"
                            >
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Create Account
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted">
                            Already have an account?
                        </span>

                        <a href="/TECHATHON/public/login">
                            Login
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>