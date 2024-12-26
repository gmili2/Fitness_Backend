<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="background-color: #dc3545; color: white;">
                    <h4>Connexion Administrateur</h4>
                </div>
                <div class="card-body" style="background-color: #f8f9fa;">
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Mot de passe" required>
                        </div>
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-danger btn-block">Connexion</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.register') }}" class="btn btn-dark btn-block">S'inscrire</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>