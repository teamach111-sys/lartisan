<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Lartisan | Centre d'Aide</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Centre d'aide L'Artisan — trouvez des réponses à vos questions sur la sécurité, les arnaques, et l'utilisation de la plateforme.">
    <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'mabrypro';
            src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }

        html,
        body {
            font-family: 'mabrypro', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="w-full bg-[#F4F4F0] min-h-screen flex flex-col">
    {{-- Navbar --}}
    <div class="bg-[#F4F4F0] max-w-[1800px] mx-3 lg:mx-14 w-full lg:w-auto">
        <nav class="lg:flex gap-5 lg:h-29 h-auto items-center justify-between py-1 mt-1">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}">
                    <img class="lg:h-full h-auto max-h-20 shrink-0" src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan">
                </a>
            </div>
            <a href="{{ route('home') }}"
                class="rounded-sm bg-[#F4F4F0] p-1 hidden lg:flex items-center gap-2 lg:w-auto px-4 border border-black h-12 my-auto cursor-pointer 
                transition-all duration-200 
                hover:-translate-x-1 hover:-translate-y-1 
                hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
                Retour au marché
            </a>
        </nav>
    </div>
    <div class="w-full border-t border-black my-2"></div>

    {{-- Hero Section --}}
    <div class="max-w-[1800px] mx-auto px-4 lg:px-14 w-full flex-grow">
        <div class="py-12 lg:py-20 text-center">
            <div class="inline-block bg-[#FF8E72] border border-black shadow-[3px_3px_0px_0px_#000000] text-black text-xs font-black px-3 py-1.5 uppercase rounded-sm mb-6">
                Centre d'aide
            </div>
            <h1 class="text-3xl lg:text-5xl font-bold mb-4">Comment pouvons-nous vous aider ?</h1>
            <p class="text-gray-600 max-w-xl mx-auto mb-8">
                Trouvez des réponses à vos questions sur la sécurité, les bonnes pratiques et l'utilisation de la plateforme L'Artisan.
            </p>

            {{-- Static Search Bar --}}
            <div class="max-w-xl mx-auto relative">
                <input id="aide-search" type="text" placeholder="Rechercher dans l'aide..."
                    class="w-full h-14 pl-12 pr-4 border-2 border-black rounded-sm bg-white text-base outline-none shadow-[4px_4px_0px_0px_#000000] focus:shadow-[6px_6px_0px_0px_#FF8E72] transition-shadow duration-200"
                    oninput="filterHelp(this.value)">
                <svg class="absolute top-[28%] left-4 size-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>

        {{-- Quick Topic Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
            <a href="#eviter-arnaques" onclick="openSection('eviter-arnaques')"
                class="bg-white border border-black rounded-sm p-6 transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer group">
                <div class="bg-[#FFF0EB] border border-black rounded-sm h-12 w-12 flex items-center justify-center mb-4 group-hover:bg-[#FF8E72] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M11.484 2.17a.75.75 0 0 1 1.032 0 11.209 11.209 0 0 0 7.877 3.08.75.75 0 0 1 .722.515 12.74 12.74 0 0 1 .635 3.985c0 5.942-4.064 10.933-9.563 12.348a.749.749 0 0 1-.374 0C6.314 20.683 2.25 15.692 2.25 9.75c0-1.39.223-2.73.635-3.985a.75.75 0 0 1 .722-.516l.143.001a11.209 11.209 0 0 0 7.734-3.08ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-1">Éviter les arnaques</h3>
                <p class="text-sm text-gray-500">Reconnaître et éviter les tentatives de fraude</p>
            </a>

            <a href="#paiement-securise" onclick="openSection('paiement-securise')"
                class="bg-white border border-black rounded-sm p-6 transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer group">
                <div class="bg-[#FFF0EB] border border-black rounded-sm h-12 w-12 flex items-center justify-center mb-4 group-hover:bg-[#FF8E72] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
                        <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-1">Paiement sécurisé</h3>
                <p class="text-sm text-gray-500">Conseils pour des transactions sûres</p>
            </a>

            <a href="#signaler-probleme" onclick="openSection('signaler-probleme')"
                class="bg-white border border-black rounded-sm p-6 transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer group">
                <div class="bg-[#FFF0EB] border border-black rounded-sm h-12 w-12 flex items-center justify-center mb-4 group-hover:bg-[#FF8E72] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M3 2.25a.75.75 0 0 1 .75.75v.54l1.838-.46a9.75 9.75 0 0 1 6.725.738l.108.054a8.25 8.25 0 0 0 5.58.652l3.109-.732a.75.75 0 0 1 .917.81 47.784 47.784 0 0 0 .005 10.337.75.75 0 0 1-.574.812l-3.114.733a9.75 9.75 0 0 1-6.594-.77l-.108-.054a8.25 8.25 0 0 0-5.69-.625l-1.81.452A.75.75 0 0 1 3 14.175V3a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-1">Signaler un problème</h3>
                <p class="text-sm text-gray-500">Comment signaler un vendeur ou une annonce</p>
            </a>

            <a href="#acheter-confiance" onclick="openSection('acheter-confiance')"
                class="bg-white border border-black rounded-sm p-6 transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer group">
                <div class="bg-[#FFF0EB] border border-black rounded-sm h-12 w-12 flex items-center justify-center mb-4 group-hover:bg-[#FF8E72] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-1">Acheter en confiance</h3>
                <p class="text-sm text-gray-500">Nos conseils pour des achats sans risques</p>
            </a>
        </div>

        {{-- Help Dropdowns --}}
        <div class="max-w-3xl mx-auto pb-20">
            {{-- Section: Éviter les arnaques --}}
            <div class="help-section mb-8" id="eviter-arnaques" data-keywords="arnaque fraude escroquerie faux vendeur méfier piège danger">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                    <span class="bg-[#FF8E72] border border-black rounded-sm h-8 w-8 flex items-center justify-center text-sm font-black">1</span>
                    Éviter les arnaques
                </h2>
                <div class="space-y-3">
                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="reconnaitre arnaque signes indices faux vendeur suspect">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment reconnaître une arnaque ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <p class="mb-3">Voici les signes qui doivent vous alerter :</p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li><strong>Prix trop bas :</strong> Si le prix est anormalement bas par rapport au marché, méfiez-vous.</li>
                                <li><strong>Pression à payer vite :</strong> Un vendeur honnête ne vous pressera jamais de payer immédiatement.</li>
                                <li><strong>Photos volées :</strong> Vérifiez si les photos ne semblent pas trop professionnelles ou déjà vues ailleurs.</li>
                                <li><strong>Comportement suspect :</strong> Méfiez-vous des vendeurs qui évitent de répondre à des questions précises sur leurs créations.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="protéger protection sécurité précautions acheter">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment me protéger en tant qu'acheteur ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Communiquez uniquement via la messagerie L'Artisan pour garder une trace de vos échanges.</li>
                                <li>Ne partagez jamais vos informations personnelles (adresse, numéro de carte) par message.</li>
                                <li>Privilégiez les rencontres en main propre dans un lieu public pour les échanges locaux.</li>
                                <li>Vérifiez toujours le produit avant de finaliser la transaction.</li>
                                <li>En cas de doute, signalez l'annonce via le bouton « Signaler » sur la page du produit.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="vendeur protéger vendre sécurité précautions">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment me protéger en tant que vendeur ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Ne remettez jamais un produit sans avoir reçu le paiement complet.</li>
                                <li>Méfiez-vous des acheteurs qui veulent payer par chèque ou virement avec un délai.</li>
                                <li>Privilégiez le paiement en espèces lors de la remise en main propre.</li>
                                <li>Ne communiquez pas vos coordonnées bancaires par message.</li>
                                <li>Signalez tout comportement suspect à notre équipe via le bouton « Signaler ».</li>
                            </ul>
                        </div>
                    </details>
                </div>
            </div>

            {{-- Section: Paiement sécurisé --}}
            <div class="help-section mb-8" id="paiement-securise" data-keywords="paiement argent transaction sécurisé payer">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                    <span class="bg-[#FF8E72] border border-black rounded-sm h-8 w-8 flex items-center justify-center text-sm font-black">2</span>
                    Paiement sécurisé
                </h2>
                <div class="space-y-3">
                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="méthode paiement espèces virement transfert">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Quelles méthodes de paiement sont sûres ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ul class="list-disc pl-5 space-y-2">
                                <li><strong>Remise en main propre :</strong> Le moyen le plus sûr. Rencontrez le vendeur dans un lieu public, vérifiez le produit et payez.</li>
                                <li><strong>Virement :</strong> Si vous devez payer à distance, soyez extrêmement vigilant et assurez-vous du sérieux de votre interlocuteur.</li>
                                <li><strong>Évitez :</strong> Les mandats, les cartes prépayées, les crypto-monnaies et tout moyen de paiement non traçable.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="distance livraison envoyer expédier colis">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment payer en toute sécurité à distance ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <p class="mb-3">Pour les achats à distance, nous recommandons :</p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Demandez des photos et vidéos supplémentaires du produit avant d'envoyer de l'argent.</li>
                                <li>Ne payez jamais la totalité à l'avance — négociez un paiement partiel si possible.</li>
                                <li>Gardez toutes les conversations dans la messagerie L'Artisan comme preuve.</li>
                            </ul>
                        </div>
                    </details>
                </div>
            </div>

            {{-- Section: Signaler un problème --}}
            <div class="help-section mb-8" id="signaler-probleme" data-keywords="signaler problème signalement report abusif">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                    <span class="bg-[#FF8E72] border border-black rounded-sm h-8 w-8 flex items-center justify-center text-sm font-black">3</span>
                    Signaler un problème
                </h2>
                <div class="space-y-3">
                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="signaler annonce produit bouton comment">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment signaler une annonce suspecte ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Rendez-vous sur la page de l'annonce que vous souhaitez signaler.</li>
                                <li>Cliquez sur le bouton <strong>« Signaler »</strong> situé sur la page du produit.</li>
                                <li>Sélectionnez le motif du signalement et ajoutez une description si nécessaire.</li>
                                <li>Notre équipe de modération examinera le signalement dans les plus brefs délais.</li>
                            </ol>

                        </div>
                    </details>

                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="vendeur comportement frauduleux utilisateur bloquer">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Que faire si un vendeur a un comportement frauduleux ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ul class="list-disc pl-5 space-y-2">
                                <li><strong>Cessez toute communication</strong> avec le vendeur suspect.</li>
                                <li><strong>Signalez l'annonce</strong> via le bouton « Signaler » sur la page du produit.</li>
                                <li><strong>Conservez les preuves :</strong> captures d'écran des conversations et photos des produits.</li>
                                <li><strong>Déposez plainte</strong> auprès des autorités compétentes si vous avez subi un préjudice financier.</li>
                            </ul>
                        </div>
                    </details>
                </div>
            </div>

            {{-- Section: Acheter en confiance --}}
            <div class="help-section mb-8" id="acheter-confiance" data-keywords="acheter confiance vérifier qualité artisan authenticité">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                    <span class="bg-[#FF8E72] border border-black rounded-sm h-8 w-8 flex items-center justify-center text-sm font-black">4</span>
                    Acheter en confiance
                </h2>
                <div class="space-y-3">
                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="vérifier vendeur fiable confiance profil">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment vérifier qu'un vendeur est fiable ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Regardez la qualité et la cohérence de ses annonces sur la plateforme.</li>
                                <li>Posez des questions détaillées sur le produit via la messagerie — un bon artisan saura répondre avec précision.</li>
                                <li>Vérifiez que les photos sont authentiques et non tirées d'internet.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="qualité produit vérifier artisanat authenticité reconnaître">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment vérifier la qualité d'un produit artisanal ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Demandez des photos en gros plan des détails et des finitions.</li>
                                <li>Renseignez-vous sur les matériaux utilisés et le processus de fabrication.</li>
                                <li>Comparez les prix avec d'autres produits similaires sur la plateforme.</li>
                                <li>Si possible, rendez-vous sur place pour examiner le produit avant achat.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="help-item bg-white border border-black rounded-sm group" data-keywords="messagerie contacter discussion communiquer">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 transition-colors">
                            Comment utiliser la messagerie L'Artisan ?
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-4 text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Sur la page d'un produit, cliquez sur <strong>« Contacter le vendeur »</strong>.</li>
                                <li>Une conversation privée sera créée entre vous et l'artisan.</li>
                                <li>Posez vos questions, négociez le prix et convenez d'un lieu de rencontre.</li>
                                <li>Toutes vos conversations sont accessibles depuis votre espace personnel.</li>
                            </ol>

                        </div>
                    </details>
                </div>
            </div>

            {{-- No Results message --}}
            <div id="no-results" class="hidden text-center py-12">
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-sm p-8">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-12 mx-auto text-gray-300 mb-4">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-gray-500 font-bold text-lg">Aucun résultat trouvé</p>
                    <p class="text-gray-400 mt-1">Essayez d'autres mots-clés pour trouver de l'aide</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="w-full bg-black text-white pt-10 pb-6 mt-auto">
        <div class="max-w-[1800px] mx-auto px-4 lg:px-14">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('imgs/logo.svg') }}" class="filter invert h-8 w-auto" alt="Lartisan Logo">
                    <span class="text-gray-500 text-sm">© 2026 Marché Artisanal. Fièrement fabriqué à Marrakech.</span>
                </div>
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#FF8E72] text-sm transition-colors">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Static search filter
        function filterHelp(query) {
            const normalizedQuery = query.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const sections = document.querySelectorAll('.help-section');
            const items = document.querySelectorAll('.help-item');
            let anyVisible = false;

            if (!query.trim()) {
                // Show everything if search is empty
                sections.forEach(s => { s.style.display = ''; });
                items.forEach(i => { i.style.display = ''; });
                document.getElementById('no-results').classList.add('hidden');
                return;
            }

            sections.forEach(section => {
                const sectionKeywords = (section.dataset.keywords || '').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const sectionTitle = section.querySelector('h2')?.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "") || '';
                let sectionHasMatch = sectionKeywords.includes(normalizedQuery) || sectionTitle.includes(normalizedQuery);

                const sectionItems = section.querySelectorAll('.help-item');
                sectionItems.forEach(item => {
                    const itemKeywords = (item.dataset.keywords || '').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    const itemText = item.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                    if (itemKeywords.includes(normalizedQuery) || itemText.includes(normalizedQuery)) {
                        item.style.display = '';
                        sectionHasMatch = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (sectionHasMatch) {
                    section.style.display = '';
                    anyVisible = true;
                } else {
                    section.style.display = 'none';
                }
            });

            if (anyVisible) {
                document.getElementById('no-results').classList.add('hidden');
            } else {
                document.getElementById('no-results').classList.remove('hidden');
            }
        }

        // Open a section when clicking topic cards
        function openSection(sectionId) {
            // Clear any search
            document.getElementById('aide-search').value = '';
            filterHelp('');

            // Open all details in that section
            setTimeout(() => {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.querySelectorAll('details').forEach(d => d.open = true);
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }

        // Handle hash on page load (from footer links)
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.replace('#', '');
            if (hash) {
                openSection(hash);
            }
        });
    </script>
</body>

</html>
