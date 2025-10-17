<?php

/**
 * Debug temporaire pour diagnostiquer le problème JobOrder
 */

// Ajouter cette fonction dans functions.php pour debug
function debug_job_order_issue($post_id)
{
	if (!$post_id) return;

	$job_id = get_field('job_id', $post_id);
	error_log("=== DEBUG JOB ORDER ===");
	error_log("Post ID: " . $post_id);
	error_log("Job ID (bullhorn_id): " . $job_id);

	if (class_exists('\SquareChilli\Bullhorn\models\JobOrder')) {
		// Chercher le job order exact
		$jobOrder = \SquareChilli\Bullhorn\models\JobOrder::find()->where(['bullhorn_id' => $job_id])->one();

		if ($jobOrder) {
			error_log("JobOrder trouvé - ID: " . $jobOrder->id . ", Status: " . $jobOrder->status);
			error_log("JobOrder isOpen: " . ($jobOrder->status === 'Open' ? 'true' : 'false'));

			// Tester findOpen
			$openJobOrder = \SquareChilli\Bullhorn\models\JobOrder::findOpen()->where(['bullhorn_id' => $job_id])->one();
			if ($openJobOrder) {
				error_log("JobOrder findOpen() trouve le job");
			} else {
				error_log("JobOrder findOpen() NE trouve PAS le job - status actuel: " . $jobOrder->status);
			}
		} else {
			error_log("Aucun JobOrder trouvé avec bullhorn_id: " . $job_id);

			// Lister quelques jobs pour comparaison
			$someJobs = \SquareChilli\Bullhorn\models\JobOrder::find()->limit(10)->all();
			error_log("Exemples de JobOrders dans la DB:");
			foreach ($someJobs as $job) {
				error_log("- ID: " . $job->id . ", Bullhorn ID: " . $job->bullhorn_id . ", Status: " . $job->status);
			}
		}
	}
	error_log("=== FIN DEBUG JOB ORDER ===");
}

// Usage: debug_job_order_issue(2904); // remplacez par l'ID du post problématique
