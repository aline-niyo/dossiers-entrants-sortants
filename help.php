<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Centre d'Aide - Plateforme SETIC</title>
    <style>
        /* Styles de base */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        header {
            background-color: #004080;
            color: white;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 1.8em;
        }
        nav {
            background-color: #0066cc;
            padding: 10px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        nav button {
            background-color: #0059b3;
            border: none;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        nav button:hover, nav button.active {
            background-color: #003d66;
        }
        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px 40px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        section {
            display: none;
        }
        section.active {
            display: block;
        }
        h2 {
            color: #004080;
            border-bottom: 2px solid #004080;
            padding-bottom: 8px;
            margin-top: 0;
        }
        /* Barre de recherche */
        #search-container {
            margin-bottom: 20px;
            text-align: center;
        }
        #search-input {
            width: 80%;
            max-width: 400px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        /* FAQ */
        .faq-item {
            margin-bottom: 15px;
        }
        .faq-question {
            font-weight: bold;
            cursor: pointer;
            background-color: #e6f0ff;
            padding: 10px;
            border-radius: 4px;
            user-select: none;
        }
        .faq-answer {
            display: none;
            padding: 10px 15px;
            border-left: 3px solid #004080;
            background-color: #f0f7ff;
            margin-top: 5px;
            border-radius: 0 4px 4px 4px;
        }
        .faq-question.active + .faq-answer {
            display: block;
        }
        /* Responsive */
        @media (max-width: 600px) {
            nav {
                flex-direction: column;
                gap: 10px;
            }
            #search-input {
                width: 100%;
            }
        }
        /* Footer */
        footer {
            text-align: center;
            padding: 15px;
            background-color: #004080;
            color: white;
            font-size: 0.9em;
        }
        a {
            color: #004080;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Centre d'Aide - Plateforme de Suivi des Dossiers au SETIC</h1>
    </header>

    <nav>
        <button class="tab-button active" data-tab="general">Utilisation Générale</button>
        <button class="tab-button" data-tab="dossiers-entrants">Dossiers Entrants</button>
        <button class="tab-button" data-tab="dossiers-sortants">Dossiers Sortants</button>
        <button class="tab-button" data-tab="notifications">Notifications & Alertes</button>
        <button class="tab-button" data-tab="technique">Support Technique</button>
        <button class="tab-button" data-tab="securite">Sécurité & Confidentialité</button>
    </nav>

    <main>
        <div id="search-container">
            <input type="text" id="search-input" placeholder="Rechercher dans l'aide..." aria-label="Recherche dans le centre d'aide" />
        </div>

        <!-- Section Utilisation Générale -->
        <section id="general" class="active">
            <h2>Utilisation Générale</h2>
            <p>Bienvenue sur la plateforme SETIC dédiée au suivi des dossiers entrants et sortants. Cette plateforme vous permet de gérer efficacement vos dossiers, suivre leur état en temps réel, et recevoir des notifications personnalisées.</p>
            <p>Pour commencer, utilisez les onglets ci-dessus pour accéder aux différentes sections d’aide.</p>
            <h3>Guide rapide :</h3>
            <ul>
                <li>Créer un compte utilisateur et vous connecter.</li>
                <li>Consulter la liste des dossiers en cours.</li>
                <li>Mettre à jour les informations d’un dossier.</li>
                <li>Recevoir des alertes en cas de changement d’état.</li>
            </ul>
        </section>

        <!-- Section Dossiers Entrants -->
        <section id="dossiers-entrants">
            <h2>Suivi des Dossiers Entrants</h2>
            <h3>Comment créer un dossier entrant ?</h3>
            <p>Pour créer un dossier entrant, cliquez sur le bouton <strong>"Nouveau dossier entrant"</strong> dans votre tableau de bord, puis remplissez le formulaire avec les informations requises.</p>
            <h3>Comment suivre l’état d’un dossier entrant ?</h3>
            <p>Accédez à la section <em>"Dossiers entrants"</em> pour voir la liste de tous vos dossiers. Chaque dossier affiche son statut actuel et la date de dernière mise à jour.</p>
            <h3>Que faire en cas de dossier bloqué ?</h3>
            <p>Si un dossier semble bloqué ou ne progresse pas, contactez le support technique via la section <em>"Support Technique"</em> ou utilisez l’option de chat en direct.</p>
        </section>

        <!-- Section Dossiers Sortants -->
        <section id="dossiers-sortants">
            <h2>Suivi des Dossiers Sortants</h2>
            <h3>Comment enregistrer un dossier sortant ?</h3>
            <p>Dans votre espace personnel, cliquez sur <strong>"Nouveau dossier sortant"</strong> et complétez les informations nécessaires. Assurez-vous de vérifier les documents joints avant validation.</p>
            <h3>Consultation des dossiers sortants</h3>
            <p>La liste des dossiers sortants est accessible depuis le menu principal. Vous pouvez filtrer par date, statut, ou destinataire.</p>
            <h3>Archivage des dossiers</h3>
            <p>Les dossiers clôturés sont automatiquement archivés. Vous pouvez les consulter dans la section <em>"Archives"</em>.</p>
        </section>

        <!-- Section Notifications & Alertes -->
        <section id="notifications">
            <h2>Notifications & Alertes</h2>
            <h3>Paramétrer vos notifications</h3>
            <p>Vous pouvez configurer vos préférences de notification dans votre profil utilisateur, choisir d’être alerté par email ou via la plateforme.</p>
            <h3>Types d’alertes</h3>
            <ul>
                <li>Changement de statut d’un dossier</li>
                <li>Nouvelle action requise</li>
                <li>Rappel de délais</li>
            </ul>
            <h3>Problèmes de réception</h3>
            <p>Si vous ne recevez pas vos notifications, vérifiez vos paramètres de messagerie et contactez le support si nécessaire.</p>
        </section>

        <!-- Section Support Technique -->
        <section id="technique">
            <h2>Support Technique</h2>
            <h3>Problèmes de connexion</h3>
            <p>Si vous avez oublié votre mot de passe, utilisez la fonction <strong>"Mot de passe oublié"</strong> sur la page de connexion.</p>
            <h3>Erreurs système courantes</h3>
            <ul>
                <li>Page blanche ou chargement infini : essayez de vider le cache de votre navigateur.</li>
                <li>Erreur 500 : contactez le support technique.</li>
            </ul>
            <h3>Contact Support</h3>
            <p>Vous pouvez joindre le support par :</p>
            <ul>
                <li>Email : <a href="mailto:support@setic.gov">support@setic.gov</a></li>
                <li>Téléphone : +33 1 23 45 67 89</li>
                <li>Chat en direct : disponible dans la plateforme</li>
            </ul>
        </section>

        <!-- Section Sécurité & Confidentialité -->
        <section id="securite">
            <h2>Sécurité & Confidentialité</h2>
            <h3>Protection des données</h3>
            <p>La plateforme respecte les normes RGPD pour garantir la confidentialité et la sécurité de vos données personnelles et professionnelles.</p>
            <h3>Bonnes pratiques</h3>
            <ul>
                <li>Ne partagez jamais vos identifiants.</li>
                <li>Utilisez un mot de passe fort et changez-le régulièrement.</li>
                <li>Déconnectez-vous après chaque session.</li>
            </ul>
            <h3>Signalement d’incidents</h3>
            <p>En cas de suspicion de violation de sécurité, contactez immédiatement le support via les coordonnées indiquées dans la section Support Technique.</p>
        </section>
    </main>

    <footer>
        &copy; 2025 SETIC - Tous droits réservés
    </footer>

    <script>
        // Gestion des onglets
        const tabButtons = document.querySelectorAll('.tab-button');
        const sections = document.querySelectorAll('main section');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Désactiver tous les boutons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                // Cacher toutes les sections
                sections.forEach(sec => sec.classList.remove('active'));

                // Activer le bouton cliqué
                button.classList.add('active');
                // Afficher la section correspondante
                const tab = button.getAttribute('data-tab');
                document.getElementById(tab).classList.add('active');

                // Reset recherche
                document.getElementById('search-input').value = '';
                filterContent('');
            });
        });

        // Fonction de recherche simple
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim().toLowerCase();
            filterContent(query);
        });

        function filterContent(query) {
            // Filtrer uniquement la section active
            const activeSection = document.querySelector('main section.active');
            if (!activeSection) return;

            // Si recherche vide, afficher tout
            if (!query) {
                Array.from(activeSection.children).forEach(child => {
                    child.style.display = '';
                });
                return;
            }

            // Parcourir tous les paragraphes, titres, listes dans la section
            Array.from(activeSection.children).forEach(child => {
                const text = child.textContent.toLowerCase();
                if (text.includes(query)) {
                    child.style.display = '';
                } else {
                    child.style.display = 'none';
                }
            });
        }

        // Gestion FAQ (questions/réponses)
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                question.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
