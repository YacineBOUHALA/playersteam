# playersteam

-pour le bon fonctionnement du projet vous devez avoir au minimum une version 8.0 de php et une version 6.0 de symfony  
 

-Notes:

	-J'ai fait en sorte que dans la page admin on gère les équipe Nationals.

      -on peux créer modifier ou supprimer une équipe nationale.

      -quand vous ajoutez une nouvelle équipe nationale, si vous voulez ajouter des joueurs a cette équipe: 

	     -Vous devez cliquer sur le bouton "See" une fois dans l'espace vous avez un bouton "add new player"
 
             	#sous note:
				Sachez que quand vous ajoutez un nouveau joueur, le système fais une vérification dans la base Donc s'il trouve que le joueur existe 				déjà et que son équipe nationale est null, il met à jour son équipe nationale en lui affectons cette équipe que vous êtes en train 				de modifier (donc il ne crée pas un nouveau joueur, il modifie juste un existant), dans le cas où il ne trouve pas le joueur dans la 				base il crée un nouveau joueur qui sera stocké dans la table Player et à qui il affectera L'équipe national en cour de modification.
    
	 -quand vous supprimez une équipe nationale, sachez que les jours de cette équipe ne seront pas supprimés leurs champs nationaTeam sera juste mis a jour 	  dans la base Player en leurs affectant une valeur null(donc ils seront sans équipe national)

     -le bouton modifier ne sert qu'a modifier le nom de l'équipe et son drapeau, si vous voulez modifier les joueurs vous devez aller dans See pour voir 	    	les détails, c'est là ou vous pourrez manipuler les jours.


