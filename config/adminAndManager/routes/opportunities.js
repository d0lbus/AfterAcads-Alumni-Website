const express = require('express');
const router = express.Router();
const opportunitiesController = require('../controllers/opportunitiesController');

router.get('/getOpportunities', opportunitiesController.getAllOpportunities);
router.get('/opportunitiesByID/:id', opportunitiesController.getOpportunityById);
router.put('/editOpportunity', opportunitiesController.editOpportunity);
router.get('/schools', opportunitiesController.getSchools);
router.get('/courses', opportunitiesController.getCoursesBySchool);
router.post('/addOpportunity', opportunitiesController.addOpportunity);
router.get('/delete/:id', opportunitiesController.deleteOpportunity);


module.exports = router;
