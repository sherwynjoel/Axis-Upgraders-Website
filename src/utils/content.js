import fs from 'fs';
import path from 'path';

export function getContent() {
  const filePath = path.join(process.cwd(), 'public', 'site-content.json');
  try {
    const rawData = fs.readFileSync(filePath, 'utf-8');
    return JSON.parse(rawData);
  } catch (error) {
    console.error('Error reading content:', error);
    return {};
  }
}
