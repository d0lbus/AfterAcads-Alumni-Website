const express = require('express');
const router = express.Router();
const opportunitiesController = require('../controllers/opportunitiesController');
const opportunityStatisticsController = require('../controllers/opportunitiesStatisticsController');

router.get('/getOpportunities', opportunitiesController.getAllOpportunities);
router.get('/opportunitiesByID/:id', opportunitiesController.getOpportunityById);
router.put('/editOpportunity', opportunitiesController.editOpportunity);
router.get('/schools', opportunitiesController.getSchools);
router.get('/courses', opportunitiesController.getCoursesBySchool);
router.post('/addOpportunity', opportunitiesController.addOpportunity);
router.get('/delete/:id', opportunitiesController.deleteOpportunity);

// Routes for opportunities statistics
router.get('/total', opportunityStatisticsController.getTotalOpportunities);
router.get('/school-course-group', opportunityStatisticsController.getOpportunitiesGroupedBySchoolAndCourse);
router.get('/monthly', opportunityStatisticsController.getOpportunitiesPerMonth);
router.get('/company', opportunityStatisticsController.getOpportunitiesGroupedByCompany);


module.exports = router;
