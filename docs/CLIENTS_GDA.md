# Les clients de GDA

GDA ne mène pas ses campagnes pour son compte : il les mène **pour des
banques**. Jusqu'en août 2026 l'application n'en connaissait qu'une, la BDM, et
le client était donc implicite — il n'apparaissait nulle part dans le schéma.

L'arrivée de la **carte GDA**, émise avec **UBA Mali**, rend cette hypothèse
fausse. Deux campagnes coexistent, avec deux réseaux de commerciaux distincts,
deux catalogues de cartes, et des données qui ne doivent jamais se mélanger.

---

## Le modèle

```
                       partenaires
                  ┌────────┴────────┐
              BDM │                 │ UBA
        (agences) │                 │ (commerciaux directs)
                  │                 │
     agences ─────┤                 ├───── (aucune agence)
   utilisateurs ──┤                 ├───── utilisateurs
     campagnes ───┤                 ├───── campagnes
  types_cartes ───┘                 └───── types_cartes
```

Une table, `partenaires`, et quatre rattachements : `agences`, `users`,
`campagnes`, `types_cartes`. Tout le reste — ventes, clients, enrôlements,
fiches téléphoniques — hérite de son client par le commercial qui a saisi.

| Colonne | Ce qu'elle décide |
|---|---|
| `code` | `bdm`, `uba` — identifiant court, affiché dans la barre latérale |
| `organisation` | `agences` ou `commerciaux` : le partenaire a-t-il un réseau d'agences ? |
| `fiche_adhesion` | La vente exige-t-elle la demande d'adhésion carte prépayée ? |
| `contrat_modele` | Quel contrat de prestation ses commerciaux signent |

C'est `organisation` qui porte l'essentiel de la différence. UBA n'a pas
d'agences : ses commerciaux dépendent directement du partenaire, et toute la
logique de périmètre par agence est court-circuitée
([`Campagne.sans_agences`](../backend/campagnes/models.py)).

---

## Qui voit quoi

| Rôle | Client |
|---|---|
| Administrateur, Direction | **Choisi** à la connexion, changeable à tout moment |
| Commercial, Commercial téléphonique | Celui de son compte, non modifiable |

Un administrateur qui se connecte est renvoyé sur `/choix-client` et y reste
tant qu'il n'a pas choisi — c'est
[`ChoixClientRequisMiddleware`](../backend/core/middleware.py) qui l'impose, une
fois pour toutes les vues plutôt que vue par vue. Le choix vit en session.

### La règle de cloisonnement

Toute requête d'un écran d'administration passe par l'une des fonctions
`filtrer_*` de [`core/partenaires.py`](../backend/core/partenaires.py) :

```python
campagnes = filtrer_campagnes(Campagne.objects.all(), partenaire_courant(request))
```

**Une requête qui ne passe pas par elles mélange les deux clients.** C'est la
seule chose à vérifier quand on ajoute un écran.

Un identifiant tapé dans la barre d'adresse ne franchit pas la cloison : les
vues chargent leurs objets via un helper `_*_du_perimetre()` qui renvoie un 404,
comme si l'objet n'existait pas.

---

## Ce qui change chez UBA

| | BDM | UBA |
|---|---|---|
| Organisation | 54 agences | 10 commerciaux directs |
| Périmètre d'une campagne | agences cochées, ou toutes | tous les commerciaux du client |
| `ventes.agence_id` | renseigné | `NULL` |
| Catalogue | 11 types de cartes | `GDA_VISA_PREPAYEE` |
| À la vente | fiche client courte | + demande d'adhésion |

Les colonnes `ventes.agence_id` et `enrolement_clients.agence_id` sont devenues
nullables pour cette raison. Les écrans qui affichent une colonne « Agence » la
masquent via la prop `aDesAgences`.

### La demande d'adhésion

UBA exige une demande d'adhésion VISA prépayée pour émettre une carte
(cf. `docs/UBA/Convention (CARTES VISA PREPAYEES AFRICARDS PERSONNE PHYSIQUE).pdf`).
Elle est saisie dans le même formulaire que la vente et enregistrée dans
`adhesions_cartes`, **en complément de la vente, pas à sa place** : rapports,
performances et primes continuent de compter des `ventes`.

Champs obligatoires : nom à imprimer sur la carte, nature et numéro de la pièce
d'identité. Le reste (naissance, nationalité, adresse, compte UBA, profession)
est facultatif à la saisie terrain.

Le volet FATCA, la personne à prévenir et le volet mineur ne sont pas saisis :
ils restent renseignés à la main sur l'imprimé.

### Le contrat de prestation

Chaque client a le sien : les engagements diffèrent, le donneur d'ordre aussi.
`partenaires.contrat_modele` nomme un modèle, et
[`campagnes/articles_defaut.py`](../backend/campagnes/articles_defaut.py) en
tient le registre.

| Modèle | Source | Articles |
|---|---|---|
| `gda_bdm` | repris du Laravel d'origine | 8 (vente), 8 (enrôlement) |
| `gda_uba` | `docs/UBA/Contrat_prestation_services_commerciaux_GDA_UBA_*.docx` | 10 |

Les articles sont **copiés dans la campagne** à son ouverture
(`campagne_contrat_articles`) : ce que le commercial a signé reste figé, même si
le modèle évolue ensuite.

**Les montants et les dates ne sont pas écrits en dur.** Le texte porte des
marqueurs — `{date_debut}`, `{emolument_forfait}`, `{forfait_carburant}`… —
remplis depuis les champs de la campagne au moment de la copie. Un contrat qui
annoncerait une échéance que l'application contredit ne vaudrait rien.

Deux contrats ne se rédigent pas pareil, et le modèle porte ces écarts :

| Clé | Ce qu'elle règle |
|---|---|
| `remuneration_dans_articles` | La BDM laisse les montants à un bloc calculé sous les articles ; UBA les énonce dans son article 4. Sans ce drapeau, le document les dirait deux fois, au risque de se contredire. |
| `date_signature` | Le « Fait à …, le … » porte le jour de l'acceptation chez la BDM, la date de prise d'effet chez UBA. |

Le pied de contrat est rendu **une seule fois**, par le document : un article
qui le reprendrait ferait apparaître deux dates différentes.

> Ajouter un client suppose donc d'écrire son contrat — c'est la seule chose
> qui ne se déduit pas. À défaut de modèle, celui de la BDM s'applique.

---

## Monter le client UBA en local

```bash
backend/.venv/Scripts/python.exe backend/manage.py migrate
backend/.venv/Scripts/python.exe scripts/preparer_campagne_uba.py
```

Le script est idempotent. Il crée le type de carte, les dix commerciaux de
`docs/UBA/LISTE DES COMMERCIAUX VENTES DE CARTES GDA.xlsx` et la campagne
d'août 2026 avec son contrat publié. Il ne touche à aucun compte existant : les
identifiants repris de la production restent valables tels quels.

### Les accès des commerciaux

```bash
backend/.venv/Scripts/python.exe scripts/acces_commerciaux_uba.py
```

Attribue à chaque commercial un mot de passe qui lui est propre et produit la
fiche Word distribuée sur le terrain, dans `docs/UBA/`. La convention est celle
retenue pour la BDM :

    initiale du prénom + deux derniers chiffres du téléphone
    + initiale du nom + « @uba »

    Fatou MAIGA, 76208554  →  F54M@uba

Le mot de passe ne fonctionne qu'avec son propre numéro : une ligne recopiée par
erreur ne donne accès à rien. Le suffixe distingue les deux clients (`@bdm` pour
la BDM) pour qu'un commercial des deux campagnes ne confonde pas ses accès.

La fiche produite contient des identifiants : elle est exclue du dépôt, comme
tous les documents de `docs/`.

> ⚠️ `scripts/preparer_comptes_reels_dev.py` fait l'inverse — il **écrase le
> mot de passe de tous les comptes** pour en poser un connu. Pratique pour se
> mettre à la place de n'importe qui, mais on ne peut plus se connecter avec
> ses identifiants habituels ensuite. Pour revenir en arrière, recharger les
> hachages depuis le dump :
>
> ```sql
> UPDATE bdm_dev.users d JOIN <base_du_dump>.users p ON p.id = d.id
>    SET d.password = p.password, d.remember_token = p.remember_token,
>        d.updated_at = p.updated_at;
> ```

## Vérifier

```bash
backend/.venv/Scripts/python.exe scripts/tester_multi_client.py \
    --admin <votre nom> --mot-de-passe-admin '<votre mot de passe>'
```

Le banc se connecte avec un **vrai compte administrateur** : les hachages venus
de la production sont conservés tels quels dans `bdm_dev`, et rien ici ne les
écrase. Seuls les comptes UBA, créés de toutes pièces, ont un mot de passe
déductible de la convention ci-dessus.

Ce banc ne se contente pas de statuts HTTP : il compte les campagnes, lit les
noms, vérifie qu'une campagne UBA renvoie 404 sous BDM, enregistre une vente
UBA complète avec sa fiche d'adhésion, contrôle en base, puis nettoie.

> **Le risque de cette fonctionnalité n'est pas qu'un écran plante : c'est
> qu'il affiche les données du mauvais client.** Un test qui vérifie seulement
> qu'une page répond 200 ne prouve rien ici.

---

## Ajouter un troisième client

1. Insérer la ligne dans `partenaires` (code, nom, `organisation`, `fiche_adhesion`).
2. Créer ses commerciaux depuis « Utilisateurs », client sélectionné.
3. Créer son catalogue depuis « Types de cartes ».
4. Créer sa campagne.

Aucun code à écrire : `organisation` et `fiche_adhesion` suffisent à décrire les
deux variantes connues. Un client avec agences se comporte comme la BDM, un
client sans agences comme UBA.

---

## Documents liés

- [STACK.md](STACK.md) — l'architecture d'ensemble
- [DEMARRAGE_MIGRATION.md](DEMARRAGE_MIGRATION.md) — monter l'environnement local
