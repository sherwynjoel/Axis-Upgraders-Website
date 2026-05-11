import fs from 'fs';
import path from 'path';

export const ALL = async ({ request }) => {
  if (request.method === 'GET') return new Response('API Active', { status: 200 });

  try {
    const text = await request.text();
    if (!text) throw new Error('No data received');

    // Parse data (it could be raw JSON or wrapped in a param)
    let data;
    if (text.startsWith('{')) {
      data = JSON.parse(text);
    } else {
      const params = new URLSearchParams(text);
      const json = params.get('json');
      if (!json) throw new Error('Could not find JSON in body');
      data = JSON.parse(json);
    }

    const filePath = path.join(process.cwd(), 'public', 'site-content.json');
    fs.writeFileSync(filePath, JSON.stringify(data, null, 2), 'utf-8');
    
    return new Response(JSON.stringify({ success: true }), { status: 200 });
  } catch (error) {
    return new Response(JSON.stringify({ error: error.message }), { status: 500 });
  }
};
