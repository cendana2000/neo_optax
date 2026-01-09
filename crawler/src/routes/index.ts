import { Router } from 'express';
import { scrappingData as pawoonScrapping } from '../controllers/pawoonController';

const router = Router();

router.all('/pawoon', pawoonScrapping);

export default router