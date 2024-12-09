const express = require('express');
const router = express.Router();
const opportunitiesController = require('../controllers/opportunitiesController');

router.get('/getOpportunities', opportunitiesController.getAllOpportunities);
router.get('/opportunities/:id', opportunitiesController.getOpportunityById);
router.put('/opportunities/edit', opportunitiesController.editOpportunity);
router.get('/schools', opportunitiesController.getSchools);
router.get('/courses', opportunitiesController.getCoursesBySchool);


module.exports = router;
